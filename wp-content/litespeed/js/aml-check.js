const API_BASE = 'https://bitmixlist-aml-242473302317.us-central1.run.app';
      let currentToken = '';
      let lookupPollHandle = null;
      let activeLookupJob = '';
      const WAITING_STATES = new Set(['queued', 'waiting', 'processing']);
      const MISTTRACK_COLORS = ['#38bdf8','#f472b6','#fb923c'];
      const UNKNOWN_SEGMENT_COLOR = '#94a3b8';
      let jobInProgress = false;
      let lastLookupSummary = null;
      let lastLookupContext = null;
      let lastLookupTimestamp = null;

      function clearAllMessages(skip = []) {
        ['tokenError', 'paymentMessage', 'lookupError'].forEach((id) => {
          if (skip.includes(id)) return;
          const el = document.getElementById(id);
          if (el) el.textContent = '';
        });
      }

      function beginButtonLoading(button, text='Loading...') {
        if (!button) {
          return () => {};
        }
        const originalText = button.textContent;
        button.disabled = true;
        button.dataset.originalText = originalText;
        button.textContent = text;
        return () => {
          button.disabled = false;
          button.textContent = button.dataset.originalText || originalText;
          delete button.dataset.originalText;
        };
      }

      async function api(path, options={}) {
        const resp = await fetch(`${API_BASE}${path}`, {
          headers: { 'Content-Type': 'application/json' },
          ...options,
        });
        if (!resp.ok) {
          const text = await resp.text();
          throw new Error(text || resp.statusText);
        }
        return resp.json();
      }

      function setLookupStatus(text='', showSpinner=false, meta='') {
        const container = document.getElementById('lookupStatus');
        const spinner = document.getElementById('lookupSpinner');
        const textEl = document.getElementById('lookupStatusText');
        const metaEl = document.getElementById('lookupJobMeta');
        textEl.textContent = text;
        metaEl.textContent = meta;
        spinner.style.display = showSpinner ? 'inline-block' : 'none';
        container.style.display = text ? 'flex' : 'none';
      }

      function clearLookupResult() {
        lastLookupSummary = null;
        lastLookupTimestamp = null;
        const container = document.getElementById('lookupVisualResult');
        if (container) {
          container.style.display = 'none';
        }
        const exportRow = document.getElementById('exportRow');
        if (exportRow) {
          exportRow.style.display = 'none';
        }
        const exportBtn = document.getElementById('exportResultBtn');
        if (exportBtn) {
          exportBtn.disabled = true;
        }
        const scoreValue = document.getElementById('amlScoreValue');
        if (scoreValue) {
          scoreValue.textContent = '--';
        }
        const riskLabel = document.getElementById('amlRiskLabel');
        if (riskLabel) {
          riskLabel.textContent = '';
          riskLabel.style.color = '#cbd5f5';
        }
        const marker = document.getElementById('amlScoreMarker');
        if (marker) {
          marker.style.left = '0%';
        }
        const pie = document.getElementById('misttrackPie');
        if (pie) {
          pie.style.background = '#1e293b';
        }
        const legend = document.getElementById('misttrackLegend');
        if (legend) {
          legend.innerHTML = '';
        }
        const chainVal = document.getElementById('chainabuseValue');
        if (chainVal) {
          chainVal.textContent = '--';
          chainVal.style.color = '#f8fafc';
        }
        const sanctionsStatus = document.getElementById('sanctionsStatus');
        if (sanctionsStatus) {
          sanctionsStatus.textContent = '--';
          sanctionsStatus.style.color = '#f8fafc';
        }
      }

      function formatScoreValue(value) {
        if (typeof value !== 'number') return null;
        return Number.isInteger(value) ? String(value) : value.toFixed(1);
      }

      function formatPercent(value) {
        if (typeof value !== 'number') return '0%';
        const formatted = value.toFixed(1);
        return `${formatted.endsWith('.0') ? formatted.slice(0, -2) : formatted}%`;
      }

      function getRiskColor(label='') {
        if (!label) return '#cbd5f5';
        const normalized = label.toLowerCase();
        if (normalized.includes('low')) return '#4ade80';
        if (normalized.includes('medium')) return '#facc15';
        if (normalized.includes('elevated') || normalized.includes('watch')) return '#f97316';
        if (normalized.includes('high') || normalized.includes('severe') || normalized.includes('critical')) return '#ef4444';
        return '#cbd5f5';
      }

      function deriveRiskLabel(score) {
        if (typeof score !== 'number') return 'No score data';
        if (score <= 33) return 'Low risk';
        if (score <= 66) return 'Medium risk';
        return 'High risk';
      }

      function normalizeLookupData(raw = {}) {
        const toNumber = (value) => {
          const num = Number(value);
          return Number.isFinite(num) ? num : null;
        };

        const amlSource = raw.amlcrypto || raw.aml_crypto || raw.amlcrypto_result || raw.aml || {};
        const scoreRaw = amlSource.score ?? amlSource.value ?? raw.amlcrypto_score ?? raw.score ?? null;
        const amlScore = toNumber(scoreRaw);
        const riskLabel = deriveRiskLabel(amlScore);

        const mistSource = raw.misttrack || raw.mist_track || raw.mistTrack || {};
        const listSource = Array.isArray(mistSource.top_counterparties)
          ? mistSource.top_counterparties
          : Array.isArray(mistSource.counterparties)
          ? mistSource.counterparties
          : [];
        const counterparties = [];
        let total = 0;
        listSource.forEach((entry) => {
          if (counterparties.length >= 3 || total >= 100) return;
          const pctRaw = entry && (entry.percentage ?? entry.percent ?? entry.share ?? entry.value ?? entry.score);
          const pct = toNumber(pctRaw);
          if (pct === null) return;
          const clamped = Math.max(0, Math.min(pct, 100));
          const remaining = Math.max(0, 100 - total);
          const share = Math.min(clamped, remaining);
          total += share;
          counterparties.push({
            label: entry.name || entry.entity || entry.counterparty || `Counterparty ${counterparties.length + 1}`,
            percentage: share,
            color: MISTTRACK_COLORS[counterparties.length] || '#38bdf8',
          });
        });
        const unknownShare = Math.max(0, 100 - total);

        const chainSource = raw.chainabuse || raw.chain_abuse || raw.chainAbuse || {};
        const chainReports = toNumber(chainSource.scam_reports ?? chainSource.reports ?? chainSource.count);

        const sanctionsValue = raw.sanctions ?? raw.sanction ?? raw.ofac ?? raw.ofsi ?? null;
        let sanctioned = null;
        const interpretSanctionString = (text='') => {
          const normalized = text.trim().toLowerCase();
          if (normalized === 'not listed' || normalized === 'not sanctioned' || normalized === 'clean') return false;
          if (normalized === 'sanctioned' || normalized === 'listed') return true;
          return null;
        };
        if (typeof sanctionsValue === 'string') {
          sanctioned = interpretSanctionString(sanctionsValue);
        } else if (typeof sanctionsValue === 'boolean') {
          sanctioned = sanctionsValue;
        } else if (sanctionsValue && typeof sanctionsValue === 'object') {
          if (typeof sanctionsValue.sanctioned === 'boolean') {
            sanctioned = sanctionsValue.sanctioned;
          } else if (typeof sanctionsValue.is_sanctioned === 'boolean') {
            sanctioned = sanctionsValue.is_sanctioned;
          } else if (typeof sanctionsValue.flagged === 'boolean') {
            sanctioned = sanctionsValue.flagged;
          } else if (typeof sanctionsValue.status === 'string') {
            sanctioned = interpretSanctionString(sanctionsValue.status);
          }
        }
        const sanctionsLabel = sanctioned === null ? 'Unknown status' : (sanctioned ? 'Sanctioned' : 'Not Sanctioned');

        return {
          amlScore,
          riskLabel,
          counterparties,
          unknownShare,
          chainReports,
          isSanctioned: sanctioned,
          sanctionsLabel,
        };
      }

      function renderLookupResult(result = {}) {
        const summary = normalizeLookupData(result);
        lastLookupSummary = summary;
        lastLookupTimestamp = new Date();
        const container = document.getElementById('lookupVisualResult');
        if (container) {
          container.style.display = 'grid';
        }
        const exportRow = document.getElementById('exportRow');
        if (exportRow) {
          exportRow.style.display = 'flex';
        }
        const exportBtn = document.getElementById('exportResultBtn');
        if (exportBtn) {
          exportBtn.disabled = false;
        }
        const scoreValue = document.getElementById('amlScoreValue');
        if (scoreValue) {
          const formattedScore = formatScoreValue(summary.amlScore);
          scoreValue.textContent = formattedScore ? `${formattedScore}/100` : 'No score';
        }
        const riskLabel = document.getElementById('amlRiskLabel');
        const riskText = summary.riskLabel || '';
        if (riskLabel) {
          riskLabel.textContent = riskText;
          riskLabel.style.color = getRiskColor(riskText);
        }
        const marker = document.getElementById('amlScoreMarker');
        if (marker) {
          if (typeof summary.amlScore === 'number') {
            const clamped = Math.max(0, Math.min(summary.amlScore, 100));
            marker.style.left = `${clamped}%`;
          } else {
            marker.style.left = '0%';
          }
        }
        renderMisttrackSection(summary);
        renderChainabuseSection(summary);
        renderSanctionsSection(summary);
      }

      function renderMisttrackSection(summary) {
        const pie = document.getElementById('misttrackPie');
        const legend = document.getElementById('misttrackLegend');
        if (!pie || !legend) return;
        const segments = [];
        let currentAngle = 0;
        legend.innerHTML = '';
        summary.counterparties.forEach((entry) => {
          const sweep = (entry.percentage / 100) * 360;
          const nextAngle = currentAngle + sweep;
          segments.push(`${entry.color} ${currentAngle}deg ${nextAngle}deg`);
          currentAngle = nextAngle;
        });
        if (summary.unknownShare > 0) {
          const sweep = (summary.unknownShare / 100) * 360;
          const nextAngle = currentAngle + sweep;
          segments.push(`${UNKNOWN_SEGMENT_COLOR} ${currentAngle}deg ${nextAngle}deg`);
        }
        if (segments.length) {
          pie.style.background = `conic-gradient(${segments.join(',')})`;
        } else {
          pie.style.background = '#1e293b';
        }
        const legendItems = summary.counterparties.map((entry) => `
          <div class="legend-item">
            <span><span class="legend-dot" style="background:${entry.color};"></span>${entry.label}</span>
            <span>${formatPercent(entry.percentage)}</span>
          </div>
        `);
        if (summary.unknownShare > 0) {
          legendItems.push(`
            <div class="legend-item">
              <span><span class="legend-dot" style="background:${UNKNOWN_SEGMENT_COLOR};"></span>Unknown</span>
              <span>${formatPercent(summary.unknownShare)}</span>
            </div>
          `);
        }
        legend.innerHTML = legendItems.length
          ? legendItems.join('')
          : '<div style="color:#94a3b8;">No counterparties reported.</div>';
      }

      function renderChainabuseSection(summary) {
        const valueEl = document.getElementById('chainabuseValue');
        if (!valueEl) return;
        if (summary.chainReports === null) {
          valueEl.textContent = 'N/A';
          valueEl.style.color = '#94a3b8';
        } else {
          valueEl.textContent = summary.chainReports;
          valueEl.style.color = summary.chainReports === 0 ? '#4ade80' : '#f87171';
        }
      }

      function renderSanctionsSection(summary) {
        const statusEl = document.getElementById('sanctionsStatus');
        if (!statusEl) return;
        statusEl.textContent = summary.sanctionsLabel;
        if (summary.isSanctioned === null) {
          statusEl.style.color = '#94a3b8';
        } else {
          statusEl.style.color = summary.isSanctioned ? '#f87171' : '#4ade80';
        }
      }

      function exportLookupResult() {
        if (!lastLookupSummary) return;
        const lines = [];
        if (lastLookupContext) {
          lines.push(`Chain: ${lastLookupContext.chain || '-'}`);
          lines.push(`Address: ${lastLookupContext.address || '-'}`);
          const timestamp = lastLookupTimestamp
            ? lastLookupTimestamp.toLocaleString()
            : new Date().toLocaleString();
          lines.push(`Date: ${timestamp}`);
          lines.push('');
        }
        const formattedScore = formatScoreValue(lastLookupSummary.amlScore);
        lines.push(`AMLCrypto Score: ${formattedScore ? `${formattedScore}/100` : 'No score data'}`);
        lines.push(`Risk: ${lastLookupSummary.riskLabel || 'Unknown'}`);
        lines.push('');
        lines.push('Counterparties:');
        if (!lastLookupSummary.counterparties.length && lastLookupSummary.unknownShare === 0) {
          lines.push('  None reported.');
        } else {
          lastLookupSummary.counterparties.forEach((entry, idx) => {
            lines.push(`  ${idx + 1}. ${entry.label}: ${formatPercent(entry.percentage)}`);
          });
          if (lastLookupSummary.unknownShare > 0) {
            lines.push(`  Unknown: ${formatPercent(lastLookupSummary.unknownShare)}`);
          }
        }
        lines.push('');
        lines.push(`Chainabuse Scam Reports: ${lastLookupSummary.chainReports === null ? 'N/A' : lastLookupSummary.chainReports}`);
        lines.push(`Sanctions Status: ${lastLookupSummary.sanctionsLabel}`);
        lines.push('Data from OFAC & OFSI');
        lines.push('');
        lines.push('AML Checker by BitMixList.org');
        const blob = new Blob([lines.join('\n')], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const anchor = document.createElement('a');
        const dateSuffix = lastLookupTimestamp
          ? lastLookupTimestamp.toISOString().replace(/[:.]/g, '-')
          : new Date().toISOString().replace(/[:.]/g, '-');
        const slug = lastLookupContext?.address
          ? `aml-result-${lastLookupContext.address.slice(0, 16)}-${dateSuffix}`
          : `aml-result-${dateSuffix}`;
        anchor.href = url;
        anchor.download = `${slug}.txt`;
        document.body.appendChild(anchor);
        anchor.click();
        document.body.removeChild(anchor);
        setTimeout(() => URL.revokeObjectURL(url), 0);
      }

      function setButtonsDisabled(disabled) {
        document.querySelectorAll('button').forEach((btn) => {
          btn.disabled = disabled;
        });
      }

      function beginJob() {
        jobInProgress = true;
        setButtonsDisabled(true);
      }

      function endJob() {
        jobInProgress = false;
        setButtonsDisabled(false);
      }

      function stopLookupPolling() {
        if (lookupPollHandle) {
          clearInterval(lookupPollHandle);
          lookupPollHandle = null;
        }
        activeLookupJob = '';
      }

      function startLookupPolling(jobId) {
        activeLookupJob = jobId;
        void pollLookupJob(jobId);
        lookupPollHandle = setInterval(() => {
          void pollLookupJob(jobId);
        }, 5000);
      }

      async function handleJobStatus(job, options = {}) {
        if (!job || !job.id) return;
        const { allowPollingStart = false } = options;
        const statusRaw = (job.status || '').toString();
        const normalizedStatus = statusRaw.toLowerCase();
        const metaParts = [`Job ID: ${job.id}`];
        if (job.attempts) {
          metaParts.push(`Attempts: ${job.attempts}`);
        }
        const metaText = metaParts.join(' · ');
        const displayTitle = statusRaw ? `Job ${statusRaw}` : 'Job status';
        setLookupStatus(
          displayTitle.charAt(0).toUpperCase() + displayTitle.slice(1),
          WAITING_STATES.has(normalizedStatus),
          metaText
        );
        if (WAITING_STATES.has(normalizedStatus)) {
          if (allowPollingStart) {
            startLookupPolling(job.id);
          }
          return;
        }
        if (normalizedStatus === 'completed') {
          stopLookupPolling();
          setLookupStatus('Job completed', false, metaText);
          document.getElementById('lookupError').textContent = '';
          renderLookupResult(job.result || {});
          await refreshCurrentTokenDetails();
          endJob();
          return;
        }
        if (normalizedStatus === 'failed') {
          stopLookupPolling();
          document.getElementById('lookupError').textContent = job.error || 'Lookup failed.';
          setLookupStatus('Job failed', false, metaText);
          endJob();
          return;
        }
        stopLookupPolling();
        setLookupStatus('Job status unavailable', false, metaText);
        endJob();
      }

      async function pollLookupJob(jobId) {
        if (!jobId || jobId !== activeLookupJob) return;
        try {
          const status = await api(`/queue/jobs/${jobId}`);
          await handleJobStatus(status);
        } catch (err) {
            stopLookupPolling();
            document.getElementById('lookupError').textContent = err.message;
            setLookupStatus('Unable to fetch job status', false);
            endJob();
        }
      }

      async function refreshCurrentTokenDetails() {
        if (!currentToken) return;
        try {
          const data = await api(`/tokens/${currentToken}`);
          renderTokenDetails(data);
          await refreshPayments();
        } catch (err) {
          document.getElementById('tokenError').textContent = err.message;
        }
      }

      async function refreshTokenDetailsOnly() {
        if (!currentToken) return;
        try {
          const data = await api(`/tokens/${currentToken}`);
          renderTokenDetails(data);
        } catch (err) {
          document.getElementById('tokenError').textContent = err.message;
        }
      }

      function renderTokenDetails(data) {
        document.getElementById('tokenDetails').innerHTML = `
            <div class="info-card">Checks available: <strong>${data.checks}</strong></div>
            <div class="info-card">Pending payments: <strong>${data.pending_payments}</strong></div>
          `;
      }

      async function loadToken(button) {
        clearAllMessages(['tokenError']);
        const token = document.getElementById('tokenInput').value.trim();
        if (!token) {
          document.getElementById('tokenError').textContent = 'Token required.';
          return;
        }
        const restore = beginButtonLoading(button, 'Loading...');
        try {
          const data = await api(`/tokens/${token}`);
          currentToken = token;
          renderTokenDetails(data);
          await refreshPayments();
        } catch (err) {
          document.getElementById('tokenError').textContent = err.message;
        } finally {
          restore();
        }
      }

      async function generateToken(button) {
        clearAllMessages(['tokenError']);
        const restore = beginButtonLoading(button, 'Generating...');
        try {
          const data = await api(`/tokens`, { method: 'POST' });
          currentToken = data.token;
          document.getElementById('tokenInput').value = data.token;
          renderTokenDetails(data);
          await refreshPayments();
          document.getElementById('paymentMessage').textContent = 'Token created. Be sure to save it securely.';
        } catch (err) {
          document.getElementById('tokenError').textContent = err.message;
        } finally {
          restore();
        }
      }

      async function runLookup() {
        clearAllMessages(['lookupError']);
        const runButton = document.getElementById('runButton');
        const maintenanceNote = document.getElementById('lookupMaintenanceNote');
        stopLookupPolling();
        setLookupStatus('', false, '');
        clearLookupResult();
        if (!currentToken) {
          document.getElementById('lookupError').textContent = 'Load a token first.';
          return;
        }
        const isAdminToken = currentToken.startsWith('pmB-');
        if (!isAdminToken) {
          document.getElementById('lookupError').textContent = 'Temporarily unavailable due to maintenance.';
          if (runButton) {
            runButton.disabled = true;
          }
          if (maintenanceNote) {
            maintenanceNote.textContent = 'Temporarily unavailable due to maintenance.';
          }
          return;
        } else if (maintenanceNote) {
          maintenanceNote.textContent = '';
          if (runButton) {
            runButton.disabled = false;
          }
        }
        const chain = document.getElementById('chainSelect').value;
        const address = document.getElementById('addressInput').value.trim();
        if (!address) {
          document.getElementById('lookupError').textContent = 'Address is required.';
          return;
        }
        if (!validateBitcoinAddress(address)) {
          document.getElementById('lookupError').textContent = 'Please enter a valid Bitcoin address.';
          return;
        }
        lastLookupContext = { chain, address };
        beginJob();
        try {
          const job = await api(`/tokens/${currentToken}/queue/aml/${chain}/${encodeURIComponent(address)}`, {
            method: 'POST',
          });
          const note = job.note ? `${job.note} · ` : '';
          setLookupStatus('Job queued', true, `${note}Job ID: ${job.job_id}`);
          startLookupPolling(job.job_id);
        } catch (err) {
          setLookupStatus('', false, '');
          document.getElementById('lookupError').textContent = parseErrorMessage(err);
          endJob();
        }
      }

      async function loadLastJob() {
        clearAllMessages(['lookupError']);
        stopLookupPolling();
        setLookupStatus('', false, '');
        clearLookupResult();
        if (!currentToken) {
          document.getElementById('lookupError').textContent = 'Load a token first.';
          return;
        }
        beginJob();
        try {
          const job = await api(`/tokens/${currentToken}/jobs/latest`);
          if (!job || !job.id) {
            document.getElementById('lookupError').textContent = 'No previous job found.';
            endJob();
            return;
          }
          const jobChain = (job.chain || '').toLowerCase() || 'bitcoin';
          const jobAddress = job.address || '';
          const chainSelect = document.getElementById('chainSelect');
          if (chainSelect) {
            chainSelect.value = jobChain;
          }
          const addressInput = document.getElementById('addressInput');
          if (addressInput) {
            addressInput.value = jobAddress;
          }
          lastLookupContext = { chain: jobChain, address: jobAddress };
          const normalizedStatus = (job.status || '').toLowerCase();
          const shouldResumePolling = WAITING_STATES.has(normalizedStatus);
          await handleJobStatus(job, { allowPollingStart: shouldResumePolling });
        } catch (err) {
          document.getElementById('lookupError').textContent = parseErrorMessage(err);
          endJob();
        }
      }

      async function createPayment() {
        clearAllMessages(['paymentMessage']);
        if (!currentToken) {
          document.getElementById('paymentMessage').textContent = 'Load a token first.';
          return;
        }
        const button = document.getElementById('createPaymentBtn');
        const originalText = button ? button.textContent : '';
        if (button) {
          button.disabled = true;
          button.textContent = 'Creating...';
        }
        try {
          const payment = await api(`/tokens/${currentToken}/payments`, { method: 'POST' });
          document.getElementById('paymentMessage').textContent = `Created payment for ${payment.amount_btc} BTC (expires ${new Date(payment.expires_at*1000).toLocaleString()}).`;
          await refreshPayments();
        } catch (err) {
          document.getElementById('paymentMessage').textContent = parseErrorMessage(err);
        } finally {
          if (button) {
            button.disabled = false;
            button.textContent = originalText || 'Buy checks';
          }
        }
      }

      async function refreshPaymentsClick(button) {
        clearAllMessages(['paymentMessage']);
        const restore = beginButtonLoading(button, 'Refreshing...');
        try {
          await refreshPayments();
        } finally {
          restore();
        }
      }

      async function refreshPayments() {
        if (!currentToken) return;
        const paymentMsgEl = document.getElementById('paymentMessage');
        paymentMsgEl.textContent = '';
        try {
          const result = await api(`/tokens/${currentToken}/payments/refresh`, { method: 'POST' });
          if (result && result.credited) {
            paymentMsgEl.textContent = `Automatically credited ${result.credited} payment(s).`;
          }
        } catch (err) {
          paymentMsgEl.textContent = parseErrorMessage(err);
        }
        try {
          const payments = await api(`/tokens/${currentToken}/payments`);
          if (!payments.length) {
            document.getElementById('paymentsTable').textContent = 'No payments yet.';
            await refreshTokenDetailsOnly();
            return;
          }
          const rows = payments.map(p => `
            <tr>
              <td>${p.id}</td>
              <td>${p.amount_btc}</td>
              <td><span class="status-pill status-${p.status}">${p.status}</span></td>
              <td>${new Date(p.created_at*1000).toLocaleString()}</td>
              <td>${new Date(p.expires_at*1000).toLocaleString()}</td>
              <td>${p.trocador_url && p.status === 'pending' ? `<a href="${p.trocador_url}" target="_blank">Pay</a>` : ''}</td>
            </tr>
          `).join('');
          document.getElementById('paymentsTable').innerHTML = `
            <table>
              <thead>
                <tr><th>ID</th><th>Amount</th><th>Status</th><th>Created</th><th>Expires</th><th>Link</th></tr>
              </thead>
              <tbody>${rows}</tbody>
            </table>`;
          await refreshTokenDetailsOnly();
        } catch (err) {
          document.getElementById('paymentsTable').textContent = parseErrorMessage(err);
        }
      }

      function parseErrorMessage(err) {
        try {
          const payload = JSON.parse(err.message);
          if (payload.detail) {
            return payload.detail;
          }
        } catch (_) {
          /* ignore */
        }
        return err.message || 'Unexpected error';
      }

      function validateBitcoinAddress(address) {
        const bech32 = /^(bc1)[0-9a-z]{25,39}$/i;
        const base58 = /^[13][a-km-zA-HJ-NP-Z1-9]{25,34}$/;
        return bech32.test(address) || base58.test(address);
      }

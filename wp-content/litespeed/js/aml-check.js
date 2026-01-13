const API_BASE = 'https://bitmixlist-aml-242473302317.us-central1.run.app';
      let currentToken = '';
      let lookupPollHandle = null;
      let activeLookupJob = '';
      const WAITING_STATES = new Set(['queued', 'waiting', 'processing']);
      const ARKHAM_COLORS = ['#38bdf8','#f472b6','#fb923c','#facc15','#c084fc','#34d399'];
      const UNKNOWN_SEGMENT_COLOR = '#94a3b8';
      let jobInProgress = false;
      let lastLookupSummary = null;
      let lastLookupContext = null;
      let lastLookupTimestamp = null;
      let chainabusePopoverOpen = false;

      function initChainabuseUi() {
        const viewBtn = document.getElementById('chainabuseViewBtn');
        const closeBtn = document.getElementById('chainabuseClosePopover');
        if (viewBtn) {
          viewBtn.addEventListener('click', () => {
            if (!viewBtn.disabled) {
              toggleChainabusePopover(true);
            }
          });
        }
        if (closeBtn) {
          closeBtn.addEventListener('click', () => toggleChainabusePopover(false));
        }
      }

      document.addEventListener('DOMContentLoaded', initChainabuseUi);

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
        const totals = document.getElementById('arkhamTotals');
        if (totals) {
          totals.innerHTML = `
            <div class="arkham-total-label">Total Volume</div>
            <div class="arkham-total-value">-- BTC</div>
            <div class="arkham-total-value">$--</div>
          `;
        }
        const pie = document.getElementById('arkhamPie');
        if (pie) {
          pie.style.background = '#1e293b';
        }
        const legend = document.getElementById('arkhamLegend');
        if (legend) {
          legend.innerHTML = '<div class="arkham-empty">No counterparties detected.</div>';
        }
        const unknown = document.getElementById('arkhamUnknown');
        if (unknown) {
          unknown.textContent = 'No unknown counterparties reported.';
        }
        const chainVal = document.getElementById('chainabuseValue');
        if (chainVal) {
          chainVal.textContent = '--';
          chainVal.style.color = '#f8fafc';
        }
        const chainViewBtn = document.getElementById('chainabuseViewBtn');
        if (chainViewBtn) {
          chainViewBtn.disabled = true;
        }
        const chainViewLink = document.getElementById('chainabuseViewLink');
        if (chainViewLink) {
          chainViewLink.style.visibility = 'hidden';
        }
        const chainPopover = document.getElementById('chainabusePopover');
        if (chainPopover) {
          chainPopover.classList.remove('open');
          chainabusePopoverOpen = false;
        }
        const reportsList = document.getElementById('chainabuseReportsList');
        if (reportsList) {
          reportsList.innerHTML = '<div class="chainabuse-empty">No reports to show.</div>';
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
        if (typeof value !== 'number' || Number.isNaN(value)) return '--%';
        const formatted = value.toFixed(1);
        return `${formatted.endsWith('.0') ? formatted.slice(0, -2) : formatted}%`;
      }

      function formatBtcAmount(value) {
        const num = Number(value);
        if (!Number.isFinite(num)) return '--';
        return num.toFixed(8);
      }

      function formatUsdAmount(value) {
        const num = Number(value);
        if (!Number.isFinite(num)) return '--';
        return num.toFixed(2);
      }

      function formatChainabuseDate(value) {
        if (!value) return 'Unknown date';
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) return 'Unknown date';
        return date.toLocaleDateString(undefined, {
          year: 'numeric',
          month: 'short',
          day: 'numeric',
        });
      }

      function toggleChainabusePopover(open) {
        const popover = document.getElementById('chainabusePopover');
        if (!popover) return;
        if (open) {
          popover.classList.add('open');
        } else {
          popover.classList.remove('open');
        }
        chainabusePopoverOpen = !!open;
      }

      function handleChainabuseOutsideClick(event) {
        if (!chainabusePopoverOpen) return;
        const popover = document.getElementById('chainabusePopover');
        const viewBtn = document.getElementById('chainabuseViewBtn');
        if (!popover) return;
        if (popover.contains(event.target)) {
          return;
        }
        if (viewBtn && viewBtn.contains(event.target)) {
          return;
        }
        toggleChainabusePopover(false);
      }

      document.addEventListener('click', handleChainabuseOutsideClick);

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

        const arkhamSource =
          raw.arkham_counterparties ||
          raw.arkhamCounterparties ||
          raw.arkham ||
          {};
        const normalizeArkhamEntity = (entry) => {
          if (!entry) return null;
          return {
            name: entry.name || entry.entity || 'Unknown entity',
            type: entry.type || null,
            totalBtc: toNumber(entry.total_btc ?? entry.totalBtc ?? entry.btc),
            totalUsd: toNumber(entry.total_usd ?? entry.totalUsd ?? entry.usd),
            percentage: toNumber(entry.percentage_btc ?? entry.percentageBtc ?? entry.percentage),
          };
        };
        const rawArkhamEntities = (Array.isArray(arkhamSource.entities) ? arkhamSource.entities : [])
          .map(normalizeArkhamEntity)
          .filter(Boolean);
        const totalsRaw = arkhamSource.totals || {};
        const totalBtc = toNumber(totalsRaw.total_btc ?? totalsRaw.totalBtc ?? totalsRaw.btc);
        const totalUsd = toNumber(totalsRaw.total_usd ?? totalsRaw.totalUsd ?? totalsRaw.usd);
        const arkhamTotals = {
          totalBtc,
          totalUsd,
        };
        const computeEntityPercentage = (entry) => {
          if (typeof entry.percentage === 'number' && !Number.isNaN(entry.percentage)) {
            return entry.percentage;
          }
          if (Number.isFinite(entry.totalBtc) && Number.isFinite(totalBtc) && totalBtc) {
            return (entry.totalBtc / totalBtc) * 100;
          }
          if (Number.isFinite(entry.totalUsd) && Number.isFinite(totalUsd) && totalUsd) {
            return (entry.totalUsd / totalUsd) * 100;
          }
          return null;
        };
        const arkhamEntities = rawArkhamEntities.map((entry) => ({
          ...entry,
          percentage: computeEntityPercentage(entry),
        }));
        const arkhamUnknownRaw = arkhamSource.unknown;
        let arkhamUnknown = arkhamUnknownRaw
          ? {
              totalBtc: toNumber(arkhamUnknownRaw.total_btc ?? arkhamUnknownRaw.totalBtc ?? arkhamUnknownRaw.btc),
              totalUsd: toNumber(arkhamUnknownRaw.total_usd ?? arkhamUnknownRaw.totalUsd ?? arkhamUnknownRaw.usd),
              percentage: toNumber(
                arkhamUnknownRaw.percentage_btc ??
                  arkhamUnknownRaw.percentageBtc ??
                  arkhamUnknownRaw.percentage
              ),
            }
          : null;
        const derivedUnknownFromTotals = () => {
          if (arkhamUnknown && (arkhamUnknown.percentage === null || Number.isNaN(arkhamUnknown.percentage))) {
            if (Number.isFinite(arkhamUnknown.totalBtc) && Number.isFinite(totalBtc) && totalBtc) {
              arkhamUnknown.percentage = (arkhamUnknown.totalBtc / totalBtc) * 100;
            } else if (Number.isFinite(arkhamUnknown.totalUsd) && Number.isFinite(totalUsd) && totalUsd) {
              arkhamUnknown.percentage = (arkhamUnknown.totalUsd / totalUsd) * 100;
            }
          }
        };
        derivedUnknownFromTotals();
        if (arkhamUnknown && (arkhamUnknown.percentage === null || Number.isNaN(arkhamUnknown.percentage))) {
          const knownPercentage = arkhamEntities.reduce(
            (sum, entry) => sum + (Number.isFinite(entry.percentage) ? entry.percentage : 0),
            0
          );
          const remaining = Math.max(0, 100 - knownPercentage);
          arkhamUnknown.percentage = remaining || null;
        }

        const chainSource = raw.chainabuse || raw.chain_abuse || raw.chainAbuse || {};
        const chainReports = toNumber(
          chainSource.scam_reports ?? chainSource.reports ?? chainSource.count
        );
        const chainReportDetailsRaw = Array.isArray(chainSource.reports_details)
          ? chainSource.reports_details
          : [];
        const chainReportDetails = chainReportDetailsRaw
          .filter((entry) => entry && entry.id)
          .map((entry) => ({
            id: entry.id,
            category: entry.category || null,
            description: entry.description || '',
            reportedAt: entry.reported_at || null,
            reportedBy: entry.reported_by || null,
            url: entry.url || null,
          }));
        const chainReportUrl = chainSource.view_url || null;

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
          arkhamEntities,
          arkhamUnknown,
          arkhamTotals,
          chainReports,
          chainReportDetails,
          chainReportUrl,
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
        renderArkhamSection(summary);
        renderChainabuseSection(summary);
        renderSanctionsSection(summary);
      }

      function renderArkhamSection(summary) {
        const totalsEl = document.getElementById('arkhamTotals');
        const pieEl = document.getElementById('arkhamPie');
        const legendEl = document.getElementById('arkhamLegend');
        const unknownEl = document.getElementById('arkhamUnknown');
        if (!totalsEl || !pieEl || !legendEl || !unknownEl) return;
        const totalBtc = formatBtcAmount(summary.arkhamTotals.totalBtc);
        const totalUsd = formatUsdAmount(summary.arkhamTotals.totalUsd);
        totalsEl.innerHTML = `
          <div class="arkham-total-label">Total Volume</div>
          <div class="arkham-total-value">${totalBtc} BTC</div>
          <div class="arkham-total-value">$${totalUsd}</div>
        `;

        const segments = [];
        let chartUsed = 0;
        const legendItems = summary.arkhamEntities.map((entry, idx) => {
          const color = ARKHAM_COLORS[idx % ARKHAM_COLORS.length];
          const percentValue = typeof entry.percentage === 'number' ? Math.max(entry.percentage, 0) : null;
          if (percentValue && chartUsed < 100) {
            const share = Math.min(percentValue, Math.max(0, 100 - chartUsed));
            if (share > 0) {
              const start = (chartUsed / 100) * 360;
              const end = ((chartUsed + share) / 100) * 360;
              segments.push(`${color} ${start}deg ${end}deg`);
              chartUsed += share;
            }
          }
          const typeText = entry.type || 'Unknown';
          return `
            <div class="arkham-legend-item">
              <div class="arkham-legend-label">
                <div class="arkham-legend-name">
                  <span class="arkham-legend-dot" style="background:${color};"></span>${entry.name}
                </div>
                <div class="arkham-legend-type">${typeText}</div>
              </div>
              <div class="arkham-legend-metrics">
                <span>${formatPercent(entry.percentage)}</span>
                <span>${formatBtcAmount(entry.totalBtc)} BTC</span>
                <span>$${formatUsdAmount(entry.totalUsd)}</span>
              </div>
            </div>
          `;
        });

        if (
          summary.arkhamUnknown &&
          (Number.isFinite(summary.arkhamUnknown.totalBtc) || Number.isFinite(summary.arkhamUnknown.totalUsd))
        ) {
          const btc = formatBtcAmount(summary.arkhamUnknown.totalBtc);
          const usd = formatUsdAmount(summary.arkhamUnknown.totalUsd);
          const percentText =
            typeof summary.arkhamUnknown.percentage === 'number'
              ? ` (${formatPercent(summary.arkhamUnknown.percentage)})`
              : '';
          unknownEl.textContent = `Unknown counterparties: ${btc} BTC ($${usd})${percentText}.`;
          const unknownPercent =
            typeof summary.arkhamUnknown.percentage === 'number'
              ? Math.max(summary.arkhamUnknown.percentage, 0)
              : Math.max(0, 100 - chartUsed);
          if (unknownPercent && chartUsed < 100) {
            const share = Math.min(unknownPercent, Math.max(0, 100 - chartUsed));
            if (share > 0) {
              const start = (chartUsed / 100) * 360;
              const end = ((chartUsed + share) / 100) * 360;
              segments.push(`${UNKNOWN_SEGMENT_COLOR} ${start}deg ${end}deg`);
              chartUsed += share;
            }
          }
        } else {
          unknownEl.textContent = 'No unknown counterparties reported.';
        }

        if (segments.length === 0) {
          pieEl.style.background = '#1e293b';
        } else {
          if (chartUsed < 100) {
            const start = (chartUsed / 100) * 360;
            segments.push(`#1e293b ${start}deg 360deg`);
          }
          pieEl.style.background = `conic-gradient(${segments.join(',')})`;
        }

        legendEl.innerHTML = legendItems.length
          ? legendItems.join('')
          : '<div class="arkham-empty">No counterparties detected.</div>';
      }

      function renderChainabuseSection(summary) {
        const valueEl = document.getElementById('chainabuseValue');
        const viewBtn = document.getElementById('chainabuseViewBtn');
        const viewLink = document.getElementById('chainabuseViewLink');
        const popover = document.getElementById('chainabusePopover');
        const reportsList = document.getElementById('chainabuseReportsList');
        const hasReports = Array.isArray(summary.chainReportDetails) && summary.chainReportDetails.length > 0;
        if (valueEl) {
          if (summary.chainReports === null) {
            valueEl.textContent = 'N/A';
            valueEl.style.color = '#94a3b8';
          } else {
            valueEl.textContent = summary.chainReports;
            valueEl.style.color = summary.chainReports === 0 ? '#4ade80' : '#f87171';
          }
        }
        if (viewBtn) {
          viewBtn.disabled = !hasReports;
        }
        if (viewLink) {
          if (summary.chainReportUrl) {
            viewLink.href = summary.chainReportUrl;
            viewLink.style.visibility = 'visible';
          } else {
            viewLink.style.visibility = 'hidden';
          }
        }
        if (!hasReports && popover) {
          popover.classList.remove('open');
          chainabusePopoverOpen = false;
        }
        if (reportsList) {
          if (hasReports) {
            reportsList.innerHTML = summary.chainReportDetails
              .map((item) => {
                const dateLabel = formatChainabuseDate(item.reportedAt);
                const category = item.category ? item.category.replace(/_/g, ' ').toLowerCase() : 'unknown';
                const safeDescription = (item.description || '').trim();
                const preview = safeDescription.length > 240 ? `${safeDescription.slice(0, 237)}...` : safeDescription;
                const link = item.url
                  ? `<a class="chainabuse-report-link" href="${item.url}" target="_blank" rel="noopener noreferrer">View report</a>`
                  : '';
                return `
                  <div class="chainabuse-report">
                    <div class="chainabuse-report-meta">
                      <span class="chainabuse-report-category">${category}</span>
                      <span>${dateLabel}</span>
                    </div>
                    <div class="chainabuse-report-body">${preview || 'No description provided.'}</div>
                    ${link}
                  </div>
                `;
              })
              .join('');
          } else {
            reportsList.innerHTML = '<div class="chainabuse-empty">No reports to show.</div>';
          }
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
        lines.push('Arkham Counterparties:');
        if (
          !lastLookupSummary.arkhamEntities.length &&
          !(
            lastLookupSummary.arkhamUnknown &&
            (Number.isFinite(lastLookupSummary.arkhamUnknown.totalBtc) ||
              Number.isFinite(lastLookupSummary.arkhamUnknown.totalUsd))
          )
        ) {
          lines.push('  None reported.');
        } else {
          lastLookupSummary.arkhamEntities.forEach((entry, idx) => {
            const typeSuffix = entry.type ? ` (${entry.type})` : '';
            const percentText = typeof entry.percentage === 'number' ? ` - ${formatPercent(entry.percentage)}` : '';
            lines.push(
              `  ${idx + 1}. ${entry.name}${typeSuffix}: ${formatBtcAmount(entry.totalBtc)} BTC ($${formatUsdAmount(
                entry.totalUsd
              )})${percentText}`
            );
          });
          if (
            lastLookupSummary.arkhamUnknown &&
            (Number.isFinite(lastLookupSummary.arkhamUnknown.totalBtc) ||
              Number.isFinite(lastLookupSummary.arkhamUnknown.totalUsd))
          ) {
            const percentText =
              typeof lastLookupSummary.arkhamUnknown.percentage === 'number'
                ? ` - ${formatPercent(lastLookupSummary.arkhamUnknown.percentage)}`
                : '';
            lines.push(
              `  Unknown: ${formatBtcAmount(lastLookupSummary.arkhamUnknown.totalBtc)} BTC ($${formatUsdAmount(
                lastLookupSummary.arkhamUnknown.totalUsd
              )})${percentText}`
            );
          }
        }
        lines.push(
          `  Totals: ${formatBtcAmount(lastLookupSummary.arkhamTotals.totalBtc)} BTC ($${formatUsdAmount(
            lastLookupSummary.arkhamTotals.totalUsd
          )})`
        );
        lines.push('');
        lines.push(
          `Chainabuse Scam Reports: ${
            lastLookupSummary.chainReports === null ? 'N/A' : lastLookupSummary.chainReports
          }`
        );
        if (lastLookupSummary.chainReportDetails.length) {
          lastLookupSummary.chainReportDetails.forEach((item, idx) => {
            const date = formatChainabuseDate(item.reportedAt);
            const category = item.category ? item.category.replace(/_/g, ' ').toLowerCase() : 'unknown';
            lines.push(
              `  ${idx + 1}. ${category} - ${date}${
                item.url ? ` (${item.url})` : ''
              }`
            );
            if (item.description) {
              const cleaned = item.description.replace(/\s+/g, ' ').trim();
              const preview = cleaned.length > 140 ? `${cleaned.slice(0, 137)}...` : cleaned;
              lines.push(`     "${preview}"`);
            }
          });
        }
        if (lastLookupSummary.chainReportUrl) {
          lines.push(`  View reports: ${lastLookupSummary.chainReportUrl}`);
        }
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
        document.getElementById('lookupError').textContent = '';
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
        if (maintenanceNote) {
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
        const packageSelect = document.getElementById('checkPackageSelect');
        const selectedChecks = packageSelect ? Number.parseInt(packageSelect.value, 10) : 100;
        const originalText = button ? button.textContent : '';
        if (button) {
          button.disabled = true;
          button.textContent = 'Creating...';
        }
        try {
          const payment = await api(`/tokens/${currentToken}/payments`, {
            method: 'POST',
            body: JSON.stringify({ checks: selectedChecks }),
          });
          document.getElementById('paymentMessage').textContent =
            `Created ${payment.checks}-check payment for ${payment.amount_btc} BTC (expires ${new Date(payment.expires_at*1000).toLocaleString()}).`;
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
              <td>${p.checks ? `${p.checks} checks` : '—'}</td>
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
                <tr><th>ID</th><th>Package</th><th>Amount</th><th>Status</th><th>Created</th><th>Expires</th><th>Link</th></tr>
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

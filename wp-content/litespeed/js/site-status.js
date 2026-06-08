(function () {
  const badges = Array.from(document.querySelectorAll('[data-site-status-id]'));
  const hasStatusFilters = document.querySelector('[data-directory-status-filter]') !== null;
  if (badges.length === 0 && !hasStatusFilters) {
    return;
  }

  const script = document.currentScript || document.querySelector('script[src*="site-status.js"]');
  const endpoint = window.BITMIXLIST_STATUS_URL || (script && script.getAttribute('data-status-url')) || 'site-status.json';
  const cacheKey = 'bitmixlist.siteStatus.v1';
  const cacheMaxAge = 70 * 60 * 1000;
  const visibilityKeys = ['directoryTextHidden', 'directoryPairHidden', 'directoryFeeHidden', 'directoryStatusHidden'];

  const labelFor = (badge, state) => {
    try {
      const labels = JSON.parse(badge.getAttribute('data-site-status-labels') || '{}');
      if (labels && labels[state]) {
        return labels[state];
      }
    } catch (error) {
      // Fall back to legacy data attributes.
    }
    const key = `status${state.charAt(0).toUpperCase()}${state.slice(1)}`;
    return badge.dataset[key] || badge.dataset.statusUnknown || state;
  };

  const normalizeState = (value) => {
    const state = String(value || '').toLowerCase();
    if (state === 'online' || state === 'offline' || state === 'unknown') {
      return state;
    }
    return 'unknown';
  };

  const staticStatusState = (item) => {
    if (!item.hasAttribute('data-directory-status-static')) {
      return null;
    }
    return normalizeState(item.getAttribute('data-directory-status-static'));
  };

  const serviceMap = (payload) => {
    if (!payload || typeof payload !== 'object') {
      return {};
    }
    if (payload.services && !Array.isArray(payload.services)) {
      return payload.services;
    }
    if (payload.statuses && !Array.isArray(payload.statuses)) {
      return payload.statuses;
    }

    const list = Array.isArray(payload.services)
      ? payload.services
      : Array.isArray(payload.statuses)
        ? payload.statuses
        : [];
    return list.reduce((items, item) => {
      if (item && item.id) {
        items[item.id] = item;
      }
      return items;
    }, {});
  };

  const checkedTitle = (badge, record, state) => {
    const label = labelFor(badge, state);
    const pieces = [label];
    if (record.checked_at) {
      pieces.push(`checked ${record.checked_at}`);
    }
    if (record.target_url) {
      pieces.push(record.target_url);
    }
    if (record.http_status) {
      pieces.push(`HTTP ${record.http_status}`);
    }
    if (record.latency_ms) {
      pieces.push(`${record.latency_ms} ms`);
    }
    if (record.error && state !== 'online') {
      pieces.push(record.error);
    }
    return pieces.join(' - ');
  };

  const setBadge = (badge, state, record) => {
    const normalized = normalizeState(state);
    badge.classList.remove(
      'directory-site-status--pending',
      'directory-site-status--online',
      'directory-site-status--offline',
      'directory-site-status--unknown'
    );
    badge.classList.add(`directory-site-status--${normalized}`);
    badge.setAttribute('data-site-status-state', normalized);
    const label = badge.querySelector('[data-site-status-text]') || badge.querySelector('[data-site-status-label]') || badge;
    label.textContent = labelFor(badge, normalized);
    badge.setAttribute('aria-label', labelFor(badge, normalized));
    badge.title = record ? checkedTitle(badge, record, normalized) : labelFor(badge, normalized);
  };

  const setFilterHidden = (item, key, isHidden) => {
    if (window.bitmixlistDirectoryFilters && window.bitmixlistDirectoryFilters.setFilterHidden) {
      window.bitmixlistDirectoryFilters.setFilterHidden(item, key, isHidden);
      return;
    }

    item.dataset[key] = isHidden ? '1' : '0';
    const hidden = visibilityKeys.some((visibilityKey) => item.dataset[visibilityKey] === '1');
    item.hidden = hidden;
    item.classList.toggle('directory-filter-hidden', hidden);
  };

  const updateAllScopeEmpty = () => {
    if (window.bitmixlistDirectoryFilters && window.bitmixlistDirectoryFilters.updateAllScopeEmpty) {
      window.bitmixlistDirectoryFilters.updateAllScopeEmpty();
      return;
    }

    const scopes = Array.from(document.querySelectorAll('[data-directory-filter-scope]'));
    for (const scope of scopes) {
      const empty = scope.querySelector('[data-directory-filter-empty]');
      if (!empty) {
        continue;
      }
      const items = Array.from(scope.querySelectorAll('[data-directory-filter-item]')).filter((item) => (
        item.closest('[data-directory-filter-scope]') === scope
      ));
      empty.hidden = items.length === 0 || items.some((item) => (
        visibilityKeys.every((visibilityKey) => item.dataset[visibilityKey] !== '1')
      ));
    }
  };

  const statusFilters = () => Array.from(document.querySelectorAll('[data-directory-status-filter]'));
  const statusItems = (scope) => Array.from(scope.querySelectorAll('[data-directory-status-item]'));

  const applyStatusFilters = () => {
    for (const filter of statusFilters()) {
      const scope = filter.closest('[data-directory-status-scope]') || document;
      const checked = filter.querySelector('input[type="radio"]:checked');
      const mode = checked ? checked.value : 'all';

      for (const item of statusItems(scope)) {
        const state = normalizeState(item.getAttribute('data-directory-status-state'));
        setFilterHidden(item, 'directoryStatusHidden', mode === 'online' && state !== 'online');
      }
    }
    updateAllScopeEmpty();
  };

  const markStatusItems = (services) => {
    const items = Array.from(document.querySelectorAll('[data-directory-status-item]'));
    for (const item of items) {
      const staticState = staticStatusState(item);
      const id = staticState === null ? item.getAttribute('data-directory-status-id') : null;
      const record = id ? services[id] : null;
      const state = staticState === null ? normalizeState(record && record.status) : staticState;
      item.setAttribute('data-directory-status-state', state);
      item.classList.remove(
        'directory-status-item--online',
        'directory-status-item--offline',
        'directory-status-item--unknown'
      );
      item.classList.add(`directory-status-item--${state}`);
    }
    applyStatusFilters();
  };

  const applyPayload = (payload) => {
    const services = serviceMap(payload);
    for (const badge of badges) {
      const id = badge.getAttribute('data-site-status-id');
      const record = id ? services[id] : null;
      if (!record) {
        setBadge(badge, 'unknown', null);
        continue;
      }
      setBadge(badge, record.status, record);
    }
    markStatusItems(services);
  };

  for (const filter of statusFilters()) {
    filter.addEventListener('change', applyStatusFilters);
  }
  applyStatusFilters();
  if (badges.length === 0) {
    return;
  }

  const applyCachedPayload = () => {
    try {
      const cached = JSON.parse(localStorage.getItem(cacheKey) || 'null');
      if (!cached || !cached.saved_at || Date.now() - cached.saved_at > cacheMaxAge) {
        return;
      }
      applyPayload(cached.payload);
    } catch (error) {
      localStorage.removeItem(cacheKey);
    }
  };

  const savePayload = (payload) => {
    try {
      localStorage.setItem(cacheKey, JSON.stringify({ saved_at: Date.now(), payload }));
    } catch (error) {
      return;
    }
  };

  const statusUrl = () => {
    const url = new URL(endpoint, window.location.href);
    url.searchParams.set('status_hour', String(Math.floor(Date.now() / (60 * 60 * 1000))));
    return url.toString();
  };

  const load = async () => {
    const controller = new AbortController();
    const timeout = window.setTimeout(() => controller.abort(), 10000);
    try {
      const response = await fetch(statusUrl(), {
        cache: 'no-store',
        headers: { Accept: 'application/json' },
        signal: controller.signal,
      });
      if (!response.ok) {
        throw new Error(`Status feed returned HTTP ${response.status}`);
      }
      const payload = await response.json();
      applyPayload(payload);
      savePayload(payload);
    } catch (error) {
      for (const badge of badges) {
        if (badge.classList.contains('directory-site-status--pending')) {
          setBadge(badge, 'unknown', { error: 'Status feed unavailable' });
        }
      }
    } finally {
      window.clearTimeout(timeout);
    }
  };

  applyCachedPayload();
  load();
}());

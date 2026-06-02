document.addEventListener('DOMContentLoaded', () => {
  const scopes = Array.from(document.querySelectorAll('[data-directory-filter-scope]'));

  const normalize = (value) => (value || '').toLowerCase().replace(/\s+/g, ' ').trim();
  const visibilityKeys = ['directoryTextHidden', 'directoryPairHidden', 'directoryFeeHidden', 'directoryStatusHidden'];
  const setFilterHidden = (item, key, isHidden) => {
    item.dataset[key] = isHidden ? '1' : '0';
    const hidden = visibilityKeys.some((visibilityKey) => item.dataset[visibilityKey] === '1');
    item.hidden = hidden;
    item.classList.toggle('directory-filter-hidden', hidden);
  };
  const itemIsVisible = (item) => (
    visibilityKeys.every((visibilityKey) => item.dataset[visibilityKey] !== '1')
  );
  const scopeItems = (scope) => Array.from(scope.querySelectorAll('[data-directory-filter-item]')).filter((item) => (
    item.closest('[data-directory-filter-scope]') === scope
  ));
  const updateScopeEmpty = (scope) => {
    const empty = scope.querySelector('[data-directory-filter-empty]');
    if (!empty) {
      return;
    }

    const items = scopeItems(scope);
    empty.hidden = items.length === 0 || items.some(itemIsVisible);
  };
  const updateAllScopeEmpty = () => {
    for (const scope of scopes) {
      updateScopeEmpty(scope);
    }
  };

  window.bitmixlistDirectoryFilters = {
    itemIsVisible,
    scopeItems,
    setFilterHidden,
    updateAllScopeEmpty,
    updateScopeEmpty,
    visibilityKeys,
  };

  for (const scope of scopes) {
    const input = scope.querySelector('[data-directory-filter-input]');
    const items = scopeItems(scope);

    if (!input || items.length === 0) {
      continue;
    }

    const applyFilter = () => {
      const query = normalize(input.value);

      for (const item of items) {
        const text = normalize(item.getAttribute('data-directory-filter-text') || item.textContent);
        const shouldShow = query === '' || text.includes(query);
        setFilterHidden(item, 'directoryTextHidden', !shouldShow);
      }

      updateScopeEmpty(scope);
    };

    input.addEventListener('input', applyFilter);
    applyFilter();
  }

  const pairFilters = Array.from(document.querySelectorAll('[data-directory-pair-filter]'));

  for (const filter of pairFilters) {
    const scope = filter.closest('[data-directory-pair-scope]') || filter.closest('.directory-detail') || document;
    const send = filter.querySelector('[data-directory-pair-send]');
    const receive = filter.querySelector('[data-directory-pair-receive]');
    const reset = filter.querySelector('[data-directory-pair-reset]');
    const status = filter.querySelector('[data-directory-pair-status]');
    const empty = filter.querySelector('[data-directory-pair-empty]');
    const items = Array.from(scope.querySelectorAll('[data-directory-pair-item]'));
    const resultItems = Array.from(scope.querySelectorAll('[data-directory-pair-result]'));
    const choiceButtons = Array.from(filter.querySelectorAll('[data-directory-pair-choice]'));

    if (!send || !receive || items.length === 0) {
      continue;
    }

    const updateChoiceButtons = () => {
      for (const button of choiceButtons) {
        const target = button.getAttribute('data-directory-pair-target');
        const select = target === 'send' ? send : target === 'receive' ? receive : null;
        const isSelected = select && button.getAttribute('data-directory-pair-value') === select.value;
        button.classList.toggle('is-selected', Boolean(isSelected));
        button.setAttribute('aria-pressed', isSelected ? 'true' : 'false');
      }
    };

    const supportsPair = (item, sendCoin, receiveCoin) => {
      if (!sendCoin || !receiveCoin) {
        return true;
      }
      if (sendCoin === receiveCoin) {
        return false;
      }

      const exactPairs = (item.getAttribute('data-directory-pairs') || '').split(/\s+/).filter(Boolean);
      if (exactPairs.length > 0) {
        return exactPairs.includes(`${sendCoin}>${receiveCoin}`);
      }

      const coins = new Set((item.getAttribute('data-directory-pair-coins') || '').split(/\s+/).filter(Boolean));
      return coins.has(sendCoin) && coins.has(receiveCoin);
    };

    const applyPairFilter = () => {
      const sendCoin = send.value;
      const receiveCoin = receive.value;
      const isActive = sendCoin !== '' && receiveCoin !== '';
      let pairMatches = 0;

      for (const item of items) {
        const shouldShow = supportsPair(item, sendCoin, receiveCoin);
        setFilterHidden(item, 'directoryPairHidden', isActive && !shouldShow);
      }

      for (const item of resultItems) {
        if (supportsPair(item, sendCoin, receiveCoin)) {
          pairMatches += 1;
        }
      }

      if (status) {
        const countPrefix = filter.getAttribute('data-directory-pair-count-prefix') || 'Matches:';
        status.textContent = isActive ? `${countPrefix} ${pairMatches}`.trim() : '';
        status.hidden = !isActive;
      }

      if (empty) {
        empty.hidden = !isActive || pairMatches !== 0;
      }

      updateChoiceButtons();
      updateAllScopeEmpty();
    };

    send.addEventListener('change', applyPairFilter);
    receive.addEventListener('change', applyPairFilter);
    for (const button of choiceButtons) {
      button.addEventListener('click', () => {
        const target = button.getAttribute('data-directory-pair-target');
        const select = target === 'send' ? send : target === 'receive' ? receive : null;
        if (!select) {
          return;
        }
        select.value = button.getAttribute('data-directory-pair-value') || '';
        select.dispatchEvent(new Event('change', { bubbles: true }));
      });
    }
    if (reset) {
      reset.addEventListener('click', () => {
        send.value = '';
        receive.value = '';
        applyPairFilter();
      });
    }
    applyPairFilter();
  }

  const feeFormatter = new Intl.NumberFormat(document.documentElement.lang || undefined, {
    maximumFractionDigits: 1,
  });
  const formatFee = (value) => feeFormatter.format(value).replace(/\.0$/, '');
  const feeFilters = Array.from(document.querySelectorAll('[data-directory-fee-filter]'));

  for (const filter of feeFilters) {
    const scope = filter.closest('[data-directory-fee-scope]') || filter.closest('.directory-detail') || document;
    const input = filter.querySelector('[data-directory-fee-input]');
    const output = filter.querySelector('[data-directory-fee-output]');
    const reset = filter.querySelector('[data-directory-fee-reset]');
    const status = filter.querySelector('[data-directory-fee-status]');
    const empty = filter.querySelector('[data-directory-fee-empty]');
    const items = Array.from(scope.querySelectorAll('[data-directory-fee-max]'));
    const resultItems = items.filter((item) => !item.closest('tbody'));

    if (!input || items.length === 0) {
      continue;
    }

    const defaultValue = input.getAttribute('data-directory-fee-default') || input.max || input.value;
    const defaultLabel = filter.getAttribute('data-directory-fee-default-label') || 'Any fee';
    const valueTemplate = filter.getAttribute('data-directory-fee-value-template') || '≤ {value}%';
    const countPrefix = filter.getAttribute('data-directory-fee-count-prefix') || 'Matching mixers:';
    let isActive = false;

    const itemFee = (item) => {
      const value = Number.parseFloat(item.getAttribute('data-directory-fee-max') || '');
      return Number.isFinite(value) ? value : null;
    };

    const activeLabel = (limit) => valueTemplate.replace('{value}', formatFee(limit));

    const applyFeeFilter = () => {
      const limit = Number.parseFloat(input.value);
      const activeLimit = Number.isFinite(limit) ? limit : Number.POSITIVE_INFINITY;
      let feeMatches = 0;

      for (const item of items) {
        const fee = itemFee(item);
        const shouldShow = !isActive || (fee !== null && fee <= activeLimit + 0.00001);
        setFilterHidden(item, 'directoryFeeHidden', !shouldShow);
      }

      for (const item of resultItems) {
        const fee = itemFee(item);
        if (fee !== null && fee <= activeLimit + 0.00001) {
          feeMatches += 1;
        }
      }

      if (output) {
        output.textContent = isActive ? activeLabel(activeLimit) : defaultLabel;
      }

      if (status) {
        status.textContent = isActive ? `${countPrefix} ${feeMatches}`.trim() : '';
        status.hidden = !isActive;
      }

      if (empty) {
        empty.hidden = !isActive || feeMatches !== 0;
      }

      updateAllScopeEmpty();
    };

    input.addEventListener('input', () => {
      isActive = true;
      applyFeeFilter();
    });

    if (reset) {
      reset.addEventListener('click', () => {
        isActive = false;
        input.value = defaultValue;
        applyFeeFilter();
      });
    }

    input.value = defaultValue;
    applyFeeFilter();
  }

  const neutralSortIndicator = '↕';
  const ascendingSortIndicator = '↑';
  const descendingSortIndicator = '↓';
  const sortTables = Array.from(document.querySelectorAll('table.directory-facts'));
  const collator = new Intl.Collator(document.documentElement.lang || undefined, {
    numeric: true,
    sensitivity: 'base',
  });
  const booleanValues = new Map([
    ['yes', 1],
    ['да', 1],
    ['no', 0],
    ['нет', 0],
  ]);
  const numericHeaderPattern = /founded|fee|minimum|maximum|limit|volume|score|year|основан|год|комисс|плата|миним|максим|лимит|объем|объём|оценк/i;

  const cellText = (row, index) => normalize((row.cells[index] && row.cells[index].textContent) || '');
  const cleanSortLabel = (value) => (value || '').replace(/[↕↑↓]/g, '').replace(/\s+/g, ' ').trim();

  const sortHeaderLabel = (header) => {
    const label = header.querySelector('.directory-sort-label');
    return cleanSortLabel((label || header).textContent);
  };

  const ensureSortHeader = (header, label) => {
    let button = header.querySelector('.directory-sort-button');
    let labelNode = button && button.querySelector('.directory-sort-label');
    let indicator = button && button.querySelector('.directory-sort-indicator');

    if (!button) {
      button = document.createElement('button');
      button.type = 'button';
      button.className = 'directory-sort-button';
      labelNode = document.createElement('span');
      labelNode.className = 'directory-sort-label';
      indicator = document.createElement('span');
      indicator.className = 'directory-sort-indicator';
      header.textContent = '';
      button.appendChild(labelNode);
      button.appendChild(indicator);
      header.appendChild(button);
    } else {
      button.type = 'button';
      button.classList.add('directory-sort-button');

      if (!labelNode) {
        const existingIndicator = indicator;
        labelNode = document.createElement('span');
        labelNode.className = 'directory-sort-label';
        button.textContent = '';
        button.appendChild(labelNode);
        if (existingIndicator) {
          button.appendChild(existingIndicator);
        }
      }

      if (!indicator) {
        indicator = document.createElement('span');
        indicator.className = 'directory-sort-indicator';
        button.appendChild(indicator);
      }
    }

    labelNode.textContent = label;
    indicator.setAttribute('aria-hidden', 'true');
    indicator.textContent = neutralSortIndicator;

    return { button, indicator };
  };

  const numericValue = (value) => {
    if (!value || /^(variable|переменная)$/i.test(value)) {
      return Number.POSITIVE_INFINITY;
    }

    const normalizedValue = value.replace(/,/g, '.').replace(/[−–—]/g, '-');
    const match = normalizedValue.match(/-?\d+(?:\.\d+)?/);
    return match ? Number.parseFloat(match[0]) : null;
  };

  const columnType = (rows, index, label) => {
    const values = rows.map((row) => cellText(row, index)).filter(Boolean);
    if (values.length > 0 && values.every((value) => booleanValues.has(value))) {
      return 'boolean';
    }

    if (numericHeaderPattern.test(label)) {
      return 'number';
    }

    return 'text';
  };

  const compareRows = (index, direction, type, originalIndexes) => (left, right) => {
    const leftText = cellText(left, index);
    const rightText = cellText(right, index);
    let result = 0;

    if (type === 'boolean') {
      result = (booleanValues.get(leftText) ?? -1) - (booleanValues.get(rightText) ?? -1);
    } else if (type === 'number') {
      const leftNumber = numericValue(leftText);
      const rightNumber = numericValue(rightText);
      if (leftNumber !== null && rightNumber !== null) {
        result = leftNumber - rightNumber;
      } else if (leftNumber !== null) {
        result = -1;
      } else if (rightNumber !== null) {
        result = 1;
      } else {
        result = collator.compare(leftText, rightText);
      }
    } else {
      result = collator.compare(leftText, rightText);
    }

    if (result !== 0) {
      return direction === 'ascending' ? result : -result;
    }

    return originalIndexes.get(left) - originalIndexes.get(right);
  };

  for (const table of sortTables) {
    const headerRow = table.tHead && table.tHead.rows[0];
    const body = table.tBodies[0];
    if (!headerRow || !body) {
      continue;
    }

    const rows = Array.from(body.rows);
    const originalIndexes = new Map(rows.map((row, index) => [row, index]));

    Array.from(headerRow.cells).forEach((header, index) => {
      const label = sortHeaderLabel(header);
      if (!label) {
        return;
      }

      header.setAttribute('aria-sort', 'none');
      header.setAttribute('data-sortable-column', '');
      const { button, indicator } = ensureSortHeader(header, label);

      button.addEventListener('click', () => {
        const direction = header.getAttribute('aria-sort') === 'ascending' ? 'descending' : 'ascending';
        const currentRows = Array.from(body.rows);
        const type = columnType(currentRows, index, label);

        Array.from(headerRow.cells).forEach((cell) => {
          cell.setAttribute('aria-sort', 'none');
          const cellIndicator = cell.querySelector('.directory-sort-indicator');
          if (cellIndicator) {
            cellIndicator.textContent = neutralSortIndicator;
          }
        });

        header.setAttribute('aria-sort', direction);
        indicator.textContent = direction === 'ascending' ? ascendingSortIndicator : descendingSortIndicator;

        currentRows
          .sort(compareRows(index, direction, type, originalIndexes))
          .forEach((row) => body.appendChild(row));
      });
    });
  }
});

(function () {
  "use strict";

  const script = document.currentScript || document.querySelector('script[src*="site-search.js"]');
  const scriptUrl = script ? new URL(script.getAttribute("src"), document.baseURI) : new URL("wp-content/litespeed/js/site-search.js", document.baseURI);
  const rootUrl = new URL(scriptUrl.href.replace(/wp-content\/litespeed\/js\/site-search\.js(?:\?.*)?$/, ""));
  const indexUrl = new URL("site-search-index.json", rootUrl).href;
  const isRu = ((document.documentElement.lang || "").toLowerCase().startsWith("ru")) || window.location.pathname.includes("/ru/");
  const text = isRu
    ? {
        label: "Поиск по сайту",
        placeholder: "Поиск",
        button: "Искать",
        loading: "Загрузка...",
        empty: "Введите запрос",
        none: "Ничего не найдено",
        open: "Открыть результат"
      }
    : {
        label: "Site search",
        placeholder: "Search",
        button: "Search",
        loading: "Loading...",
        empty: "Type to search",
        none: "No results found",
        open: "Open result"
      };
  const directorySections = isRu
    ? [
        ["Миксеры", "/ru/mixers/"],
        ["Обмен без KYC", "/ru/neverkyc-exchanges/"],
        ["Обмен мгновенный", "/ru/instant-exchanges/"],
        ["P2P-площадки", "/ru/p2p-markets/"],
        ["Координаторы", "/ru/coordinators/"],
        ["Инструменты приватности", "/ru/privacy-tools/"]
      ]
    : [
        ["Mixers", "/mixers/"],
        ["Exchange KYC-Free", "/neverkyc-exchanges/"],
        ["Exchange Instant", "/instant-exchanges/"],
        ["P2P Marketplaces", "/p2p-markets/"],
        ["Coordinators", "/coordinators/"],
        ["Privacy Tools", "/privacy-tools/"]
      ];

  let indexPromise = null;

  function ready(callback) {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", callback);
    } else {
      callback();
    }
  }

  function loadIndex() {
    if (!indexPromise) {
      indexPromise = fetch(indexUrl, { credentials: "same-origin" })
        .then(function (response) {
          if (!response.ok) {
            throw new Error("Search index failed to load");
          }
          return response.json();
        })
        .then(function (data) {
          return Array.isArray(data.entries) ? data.entries : [];
        });
    }

    return indexPromise;
  }

  function normalize(value) {
    return (value || "").toString().toLowerCase().replace(/\s+/g, " ").trim();
  }

  function tokenize(query) {
    return normalize(query).split(" ").filter(Boolean).slice(0, 8);
  }

  function scoreEntry(entry, query, tokens) {
    const title = normalize(entry.title);
    const description = normalize(entry.description);
    const section = normalize(entry.section);
    const url = normalize(entry.url);
    const body = normalize(entry.text);
    const phrase = normalize(query);
    let score = entry.locale === (isRu ? "ru" : "en") ? 2 : 0;

    if (phrase && title.includes(phrase)) score += 24;
    if (phrase && description.includes(phrase)) score += 10;
    if (phrase && url.includes(phrase)) score += 8;

    for (const token of tokens) {
      if (title.includes(token)) score += 12;
      if (section.includes(token)) score += 8;
      if (description.includes(token)) score += 5;
      if (url.includes(token)) score += 4;
      if (body.includes(token)) score += 2;
    }

    return score;
  }

  function searchEntries(entries, query) {
    const tokens = tokenize(query);
    if (!tokens.length) {
      return [];
    }

    const currentLocale = isRu ? "ru" : "en";

    return entries
      .filter(function (entry) {
        return entry.locale === currentLocale;
      })
      .map(function (entry) {
        return { entry: entry, score: scoreEntry(entry, query, tokens) };
      })
      .filter(function (item) {
        return item.score > 0 && tokens.every(function (token) {
          return normalize(item.entry.title + " " + item.entry.description + " " + item.entry.section + " " + item.entry.url + " " + item.entry.text).includes(token);
        });
      })
      .sort(function (a, b) {
        return b.score - a.score || a.entry.title.localeCompare(b.entry.title);
      })
      .slice(0, 8)
      .map(function (item) {
        return item.entry;
      });
  }

  function resultHref(url) {
    if (!url) {
      return rootUrl.href;
    }
    if (url.startsWith("/")) {
      return new URL(url.slice(1), rootUrl).href;
    }
    return new URL(url, rootUrl).href;
  }

  function renderResults(panel, results, stateText) {
    panel.textContent = "";
    panel.hidden = false;

    if (stateText) {
      const status = document.createElement("div");
      status.className = "site-search__status";
      status.textContent = stateText;
      panel.appendChild(status);
      return;
    }

    const list = document.createElement("ul");
    list.className = "site-search__results";

    for (const result of results) {
      const item = document.createElement("li");
      const link = document.createElement("a");
      const title = document.createElement("span");
      const meta = document.createElement("span");
      const description = document.createElement("span");

      link.className = "site-search__result";
      link.href = resultHref(result.url);
      link.setAttribute("aria-label", text.open + ": " + result.title);
      title.className = "site-search__title";
      meta.className = "site-search__meta";
      description.className = "site-search__description";
      title.textContent = result.title;
      meta.textContent = result.section || result.url;
      description.textContent = result.description || result.url;

      link.append(title, meta, description);
      item.appendChild(link);
      list.appendChild(item);
    }

    panel.appendChild(list);
  }

  function headerTitleSize(length, isCompact) {
    if (isCompact) {
      if (length > 66) return "0.68rem";
      if (length > 54) return "0.74rem";
      if (length > 44) return "0.82rem";
      if (length > 34) return "0.9rem";
      if (length > 26) return "1rem";
      return "1.15rem";
    }

    if (length > 82) return "0.9rem";
    if (length > 68) return "0.98rem";
    if (length > 56) return "1.08rem";
    if (length > 46) return "1.18rem";
    if (length > 36) return "1.3rem";
    if (length > 30) return "1.4rem";
    return "1.5rem";
  }

  function initHeaderTitleSizing() {
    const title = document.querySelector(".site-header .header-inner > h4");
    if (!title) {
      return;
    }

    const textLength = Array.from(title.textContent.trim()).length;
    const compactQuery = window.matchMedia("(max-width: 700px)");
    const compactSizes = ["1.15rem", "1rem", "0.9rem", "0.82rem", "0.74rem", "0.68rem", "0.62rem"];
    const desktopSizes = ["1.5rem", "1.4rem", "1.3rem", "1.18rem", "1.08rem", "0.98rem", "0.9rem", "0.82rem"];

    function applySize() {
      const sizes = compactQuery.matches ? compactSizes : desktopSizes;
      const base = headerTitleSize(textLength, compactQuery.matches);
      let startIndex = sizes.indexOf(base);

      if (startIndex === -1) {
        startIndex = 0;
      }

      title.style.fontSize = sizes[startIndex];

      requestAnimationFrame(function () {
        for (let index = startIndex; index < sizes.length; index += 1) {
          title.style.fontSize = sizes[index];
          if (title.scrollWidth <= title.clientWidth || index === sizes.length - 1) {
            break;
          }
        }
      });
    }

    applySize();
    window.addEventListener("resize", applySize);
  }

  function initHeaderNav() {
    const header = document.querySelector(".site-header");
    if (!header || header.querySelector(".directory-meta-nav")) {
      return;
    }

    const nav = document.createElement("nav");
    const currentPath = window.location.pathname.replace(/\/+$/, "/");
    nav.className = "directory-meta-nav";
    nav.setAttribute("aria-label", isRu ? "Разделы каталога" : "Directory sections");

    for (const section of directorySections) {
      const label = section[0];
      const path = section[1];
      const link = document.createElement("a");
      const isActive = currentPath === path || currentPath.startsWith(path);

      link.className = "directory-meta-link" + (isActive ? " is-active" : "");
      link.href = new URL(path.replace(/^\//, ""), rootUrl).href;
      link.textContent = label;
      if (isActive) {
        link.setAttribute("aria-current", "true");
      }
      nav.appendChild(link);
    }

    header.appendChild(nav);
  }

  function initSearch() {
    const headerInner = document.querySelector(".site-header .header-inner");
    const languageSwitcher = headerInner ? headerInner.querySelector(".lang-switcher") : null;
    if (!headerInner || headerInner.querySelector(".site-search")) {
      return;
    }

    const form = document.createElement("form");
    const label = document.createElement("label");
    const control = document.createElement("div");
    const input = document.createElement("input");
    const button = document.createElement("button");
    const icon = document.createElement("span");
    const panel = document.createElement("div");
    let activeQuery = "";
    let timer = 0;

    form.className = "site-search site-search--header";
    form.setAttribute("role", "search");
    form.autocomplete = "off";
    label.className = "screen-reader-text";
    label.setAttribute("for", "site-search-input");
    label.textContent = text.label;
    control.className = "site-search__control";
    input.id = "site-search-input";
    input.className = "site-search__input";
    input.type = "search";
    input.inputMode = "search";
    input.placeholder = text.placeholder;
    input.setAttribute("aria-label", text.label);
    input.setAttribute("aria-controls", "site-search-panel");
    button.className = "site-search__button";
    button.type = "submit";
    button.setAttribute("aria-label", text.button);
    icon.setAttribute("aria-hidden", "true");
    icon.innerHTML = '<svg class="site-search__icon" viewBox="0 0 24 24" focusable="false" aria-hidden="true"><circle cx="11" cy="11" r="6.5"></circle><path d="m16 16 4 4"></path></svg>';
    panel.id = "site-search-panel";
    panel.className = "site-search__panel";
    panel.hidden = true;

    button.appendChild(icon);
    control.append(input, button);
    form.append(label, control, panel);
    headerInner.insertBefore(form, languageSwitcher);

    function runSearch() {
      const query = input.value.trim();
      activeQuery = query;

      if (!query) {
        renderResults(panel, [], text.empty);
        return;
      }

      renderResults(panel, [], text.loading);
      loadIndex()
        .then(function (entries) {
          if (activeQuery !== query) {
            return;
          }
          const results = searchEntries(entries, query);
          renderResults(panel, results, results.length ? "" : text.none);
        })
        .catch(function () {
          renderResults(panel, [], text.none);
        });
    }

    input.addEventListener("input", function () {
      window.clearTimeout(timer);
      timer = window.setTimeout(runSearch, 120);
    });

    input.addEventListener("focus", function () {
      if (input.value.trim()) {
        runSearch();
      } else {
        renderResults(panel, [], text.empty);
      }
    });

    form.addEventListener("submit", function (event) {
      event.preventDefault();
      if (!input.value.trim()) {
        input.focus();
        renderResults(panel, [], text.empty);
        return;
      }

      loadIndex().then(function (entries) {
        const results = searchEntries(entries, input.value.trim());
        if (results[0]) {
          window.location.href = resultHref(results[0].url);
        } else {
          renderResults(panel, [], text.none);
        }
      });
    });

    document.addEventListener("click", function (event) {
      if (!form.contains(event.target)) {
        panel.hidden = true;
      }
    });

    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape") {
        panel.hidden = true;
        input.blur();
      }
    });
  }

  ready(function () {
    initSearch();
    initHeaderNav();
    initHeaderTitleSizing();
  });
})();

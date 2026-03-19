(function () {
  const AD_SERVER_ORIGIN = 'https://bitmixlist-adserver-242473302317.us-central1.run.app';
  const DEFAULT_AD_URL = 'https://mixtum.io';
  const DESKTOP_BREAKPOINT = 768;
  let activeSlotType = null;
  let resizeHandlerBound = false;
  let renderToken = 0;

  function getViewportWidth() {
    return window.innerWidth || document.documentElement.clientWidth || 0;
  }

  function getSlotType(width) {
    return width >= DESKTOP_BREAKPOINT ? 'desktop' : 'mobile';
  }

  function getSlot(type) {
    return document.querySelector(type === 'desktop' ? '.sylvester-top' : '.sylvester-top-mobile');
  }

  function getDefaultAd(width) {
    return {
      clickUrl: DEFAULT_AD_URL,
      imageUrl: `${AD_SERVER_ORIGIN}/tweet?device-width=${width}&ad=0`,
    };
  }

  async function fetchAd(width) {
    const response = await window.fetch(`${AD_SERVER_ORIGIN}/ad?device-width=${width}`, {
      credentials: 'omit',
      mode: 'cors',
      cache: 'no-store',
    });

    if (!response.ok) {
      throw new Error(`Ad request failed with status ${response.status}`);
    }

    return response.json();
  }

  function createTrackedAd(adData) {
    const link = document.createElement('a');
    link.href = adData.clickUrl;
    link.rel = 'sponsored noopener noreferrer';

    const image = document.createElement('img');
    image.src = adData.imageUrl;
    image.alt = 'Sponsored banner';
    image.decoding = 'async';
    image.style.maxWidth = '100%';
    image.style.height = 'auto';
    image.style.display = 'block';

    link.appendChild(image);
    return link;
  }

  async function renderTopAd(forceReload) {
    const width = getViewportWidth();
    const slotType = getSlotType(width);
    const slot = getSlot(slotType);
    const otherSlot = getSlot(slotType === 'desktop' ? 'mobile' : 'desktop');
    const currentToken = ++renderToken;

    if (!slot) {
      return;
    }

    if (otherSlot && (forceReload || activeSlotType !== slotType)) {
      otherSlot.textContent = '';
      delete otherSlot.dataset.adLoaded;
    }

    if (!forceReload && slot.dataset.adLoaded === 'true') {
      activeSlotType = slotType;
      return;
    }

    let adData = getDefaultAd(width);

    try {
      adData = await fetchAd(width);
    } catch (error) {
      console.error('Failed to load ad metadata:', error);
    }

    if (currentToken !== renderToken) {
      return;
    }

    slot.textContent = '';
    slot.appendChild(createTrackedAd(adData));
    slot.dataset.adLoaded = 'true';
    activeSlotType = slotType;
  }

  function ensureResponsiveBinding() {
    if (resizeHandlerBound) {
      return;
    }

    const rerenderIfNeeded = function () {
      const nextSlotType = getSlotType(getViewportWidth());
      if (nextSlotType !== activeSlotType) {
        renderTopAd(true);
      }
    };

    window.addEventListener('resize', rerenderIfNeeded, { passive: true });
    window.addEventListener('orientationchange', function () {
      window.setTimeout(function () {
        renderTopAd(true);
      }, 0);
    });
    resizeHandlerBound = true;
  }

  window.bitmixlistLoadTopAd = function bitmixlistLoadTopAd() {
    ensureResponsiveBinding();
    renderTopAd(false);
  };
})();

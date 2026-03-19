(function () {
  const AD_SERVER_ORIGIN = 'https://bitmixlist-adserver-242473302317.us-central1.run.app';
  const DIRECT_AD_URL = 'https://mixtum.io';
  const DESKTOP_BREAKPOINT = 768;
  let activeSlotType = null;
  let resizeHandlerBound = false;

  function getViewportWidth() {
    return window.innerWidth || document.documentElement.clientWidth || 0;
  }

  function getSlotType(width) {
    return width >= DESKTOP_BREAKPOINT ? 'desktop' : 'mobile';
  }

  function getSlot(type) {
    return document.querySelector(type === 'desktop' ? '.sylvester-top' : '.sylvester-top-mobile');
  }

  function createTrackedAd(width) {
    const link = document.createElement('a');
    link.href = DIRECT_AD_URL;
    link.rel = 'sponsored noopener noreferrer';

    const image = document.createElement('img');
    image.src = `${AD_SERVER_ORIGIN}/tweet?device-width=${width}`;
    image.alt = 'Sponsored banner';
    image.decoding = 'async';
    image.style.maxWidth = '100%';
    image.style.height = 'auto';
    image.style.display = 'block';

    link.appendChild(image);
    return link;
  }

  function renderTopAd(forceReload) {
    const width = getViewportWidth();
    const slotType = getSlotType(width);
    const slot = getSlot(slotType);
    const otherSlot = getSlot(slotType === 'desktop' ? 'mobile' : 'desktop');

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

    slot.textContent = '';
    slot.appendChild(createTrackedAd(width));
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

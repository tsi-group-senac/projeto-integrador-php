(() => {
  const nav = document.querySelector('[data-mobile-nav]');
  const toggle = document.querySelector('[data-mobile-nav-toggle]');

  if (nav && toggle) {
    const iconOpen = toggle.querySelector('[data-icon-open]');
    const iconClose = toggle.querySelector('[data-icon-close]');

    toggle.addEventListener('click', () => {
      const expanded = toggle.getAttribute('aria-expanded') === 'true';
      const next = !expanded;
      toggle.setAttribute('aria-expanded', next ? 'true' : 'false');
      nav.classList.toggle('hidden', !next);
      if (iconOpen && iconClose) {
        iconOpen.classList.toggle('hidden', next);
        iconClose.classList.toggle('hidden', !next);
      }
    });
  }

  const bootIcons = () => {
    if (typeof lucide === 'undefined' || typeof lucide.createIcons !== 'function') {
      return;
    }

    lucide.createIcons({
      attrs: {
        'stroke-width': 1.75,
      },
    });
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootIcons);
  } else {
    bootIcons();
  }
})();

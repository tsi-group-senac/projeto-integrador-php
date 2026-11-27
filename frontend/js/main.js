(() => {
  const nav = document.querySelector('[data-mobile-nav]');
  const toggle = document.querySelector('[data-mobile-nav-toggle]');

  if (nav && toggle) {
    toggle.addEventListener('click', () => {
      const expanded = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
      nav.classList.toggle('hidden');
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

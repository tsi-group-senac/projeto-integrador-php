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
})();

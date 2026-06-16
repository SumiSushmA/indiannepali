(function () {
  const eyePath = 'M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z';
  const eyeOffPath = 'M17.9 17.9A10 10 0 0 1 12 20C5 20 1 12 1 12a18.8 18.8 0 0 1 5.1-7.1M9.9 9.9a3 3 0 1 0 4.2 4.2M22 12s-3.5-7-10-7a10 10 0 0 0-5.3 1.5M3 3l18 18';

  function setToggleIcon(btn, visible) {
    const path = btn.querySelector('path');
    if (!path) return;
    path.setAttribute('d', visible ? eyeOffPath : eyePath);
    btn.setAttribute('aria-label', visible ? 'Hide password' : 'Show password');
  }

  document.querySelectorAll('[data-pass-toggle]').forEach((btn) => {
    const wrap = btn.closest('.acct-pass-wrap');
    const input = wrap?.querySelector('.acct-pass-inp');
    if (!input) return;

    btn.addEventListener('click', () => {
      const show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      setToggleIcon(btn, show);
    });
  });
})();

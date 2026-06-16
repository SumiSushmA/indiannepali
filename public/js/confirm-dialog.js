(function () {
  let modal;
  let resolveCurrent;

  function ensureModal() {
    if (modal) return modal;

    modal = document.createElement('div');
    modal.className = 'app-confirm';
    modal.hidden = true;
    modal.innerHTML = `
      <div class="app-confirm__backdrop" data-app-confirm-cancel></div>
      <div class="app-confirm__panel" role="dialog" aria-modal="true" aria-labelledby="app-confirm-title">
        <h3 id="app-confirm-title" class="app-confirm__title">Please confirm</h3>
        <p class="app-confirm__message" id="app-confirm-message"></p>
        <div class="app-confirm__actions">
          <button type="button" class="btn btn-ghost btn-sm" data-app-confirm-cancel>Cancel</button>
          <button type="button" class="btn btn-gold btn-sm" data-app-confirm-ok>Confirm</button>
        </div>
      </div>
    `;

    document.body.appendChild(modal);

    const close = (result) => {
      if (modal.hidden) return;
      modal.hidden = true;
      document.body.classList.remove('app-confirm-open');
      if (resolveCurrent) {
        resolveCurrent(result);
        resolveCurrent = null;
      }
    };

    modal.querySelectorAll('[data-app-confirm-cancel]').forEach((el) => {
      el.addEventListener('click', () => close(false));
    });
    modal.querySelector('[data-app-confirm-ok]')?.addEventListener('click', () => close(true));
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') close(false);
    });

    return modal;
  }

  function appConfirm(message, title) {
    const el = ensureModal();
    el.querySelector('#app-confirm-message').textContent = message || 'Are you sure?';
    el.querySelector('#app-confirm-title').textContent = title || 'Please confirm';
    el.hidden = false;
    document.body.classList.add('app-confirm-open');

    return new Promise((resolve) => {
      resolveCurrent = resolve;
    });
  }

  window.appConfirm = appConfirm;

  document.addEventListener('submit', async (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement)) return;
    const message = form.getAttribute('data-confirm');
    if (!message) return;
    if (form.dataset.confirmed === '1') {
      delete form.dataset.confirmed;
      return;
    }

    event.preventDefault();
    const ok = await appConfirm(message);
    if (!ok) return;

    form.dataset.confirmed = '1';
    form.requestSubmit ? form.requestSubmit() : form.submit();
  });
})();

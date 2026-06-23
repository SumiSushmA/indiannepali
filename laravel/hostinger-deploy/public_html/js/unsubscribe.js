(function () {
  const dialog = document.getElementById('unsub-confirm-dialog');
  const openBtn = document.getElementById('unsub-open-dialog');
  const cancelBtn = document.getElementById('unsub-cancel');

  if (!dialog || !openBtn) return;

  openBtn.addEventListener('click', () => {
    if (typeof dialog.showModal === 'function') {
      dialog.showModal();
    }
  });

  cancelBtn?.addEventListener('click', () => dialog.close());
  dialog.addEventListener('click', (e) => {
    if (e.target === dialog) dialog.close();
  });
})();

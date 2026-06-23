(function () {
  function syncForm(form) {
    const cta = form.querySelector('.adm-promo-cta-type')?.value;
    const offer = form.querySelector('.adm-promo-offer-type')?.value;

    form.querySelectorAll('.adm-promo-menu-item').forEach((el) => {
      el.style.display = cta === 'order_item' ? 'grid' : 'none';
    });

    form.querySelectorAll('.adm-promo-min-order').forEach((el) => {
      el.style.display = offer === 'spend_save' ? 'grid' : 'none';
    });

    form.querySelectorAll('.adm-promo-min-party').forEach((el) => {
      el.style.display = offer === 'reservation_perk' || cta === 'reserve' ? 'grid' : 'none';
    });
  }

  document.querySelectorAll('.adm-promo-form').forEach((form) => {
    syncForm(form);
    form.querySelector('.adm-promo-cta-type')?.addEventListener('change', () => syncForm(form));
    form.querySelector('.adm-promo-offer-type')?.addEventListener('change', () => syncForm(form));
  });
})();

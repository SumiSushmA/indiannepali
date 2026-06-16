(function () {
  const form = document.getElementById('checkout-form');
  if (!form) return;

  const subtotal = parseFloat(form.dataset.subtotal) || 0;
  const taxRate = parseFloat(form.dataset.taxRate) || 0.0875;
  const deliveryFeeAmount = parseFloat(form.dataset.deliveryFee) || 3.99;
  const freeDeliveryMin = parseFloat(form.dataset.freeDeliveryMin) || 40;

  const el = {
    tax: document.getElementById('checkout-tax'),
    deliveryRow: document.getElementById('checkout-delivery-row'),
    delivery: document.getElementById('checkout-delivery'),
    tipRow: document.getElementById('checkout-tip-row'),
    tip: document.getElementById('checkout-tip'),
    total: document.getElementById('checkout-total'),
    submitTotal: document.getElementById('checkout-submit-total'),
  };

  function money(n) {
    return '$' + n.toFixed(2);
  }

  function getMode() {
    return form.querySelector('input[name="mode"]:checked')?.value || 'delivery';
  }

  function getTipRate() {
    return parseFloat(form.querySelector('input[name="tip"]:checked')?.value || '0.18');
  }

  function deliveryFee(mode) {
    if (mode !== 'delivery') return 0;
    return subtotal >= freeDeliveryMin ? 0 : deliveryFeeAmount;
  }

  function recalc() {
    const mode = getMode();
    const tipRate = getTipRate();
    const tax = Math.round(subtotal * taxRate * 100) / 100;
    const fee = deliveryFee(mode);
    const tipAmount = Math.round(subtotal * tipRate * 100) / 100;
    const total = Math.round((subtotal + tax + fee + tipAmount) * 100) / 100;

    if (el.tax) el.tax.textContent = money(tax);

    if (el.deliveryRow) {
      el.deliveryRow.style.display = mode === 'delivery' ? '' : 'none';
    }
    if (el.delivery) {
      el.delivery.textContent = fee === 0 ? 'Free' : money(fee);
      el.delivery.style.color = fee === 0 ? 'var(--leaf-500)' : 'var(--cream-2)';
    }

    const deliveryNote = document.getElementById('checkout-delivery-note');
    if (deliveryNote) {
      deliveryNote.style.display = mode === 'delivery' && fee === 0 ? '' : 'none';
    }

    if (el.tipRow) {
      el.tipRow.style.display = tipAmount > 0 ? '' : 'none';
    }
    if (el.tip) el.tip.textContent = money(tipAmount);

    if (el.total) el.total.textContent = money(total);
    if (el.submitTotal) el.submitTotal.textContent = total.toFixed(2);

    document.querySelectorAll('.cust-delivery-field').forEach(function (field) {
      field.style.display = mode === 'delivery' ? '' : 'none';
      const input = field.querySelector('input');
      if (input) {
        if (mode === 'delivery') {
          if (field.querySelector('[name="address"]')) input.required = true;
        } else {
          input.required = false;
        }
      }
    });
  }

  form.querySelectorAll('input[name="mode"], input[name="tip"]').forEach(function (input) {
    input.addEventListener('change', recalc);
  });

  recalc();
})();

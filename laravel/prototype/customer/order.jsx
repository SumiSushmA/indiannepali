/* Order / menu page + checkout → window.Order, window.Checkout */
(function () {
  const { useContext, useState, useEffect, useRef } = React;
  const h = React.createElement;
  const Icon = window.Icon, Ph = window.Ph;

  function PageHead({ eyebrow, title, sub }) {
    return h('div', { style: { paddingTop: 130, paddingBottom: 8, textAlign: 'center', maxWidth: 720, margin: '0 auto' } },
      h('div', { className: 'eyebrow center', style: { justifyContent: 'center', marginBottom: 16 } }, eyebrow),
      h('h1', { style: { fontSize: 'clamp(38px,5vw,62px)', lineHeight: 1.03 } }, title),
      sub && h('p', { style: { color: 'var(--sand)', fontSize: 17, lineHeight: 1.65, marginTop: 18 } }, sub));
  }

  function Order() {
    const { addItem } = useContext(window.ShopContext);
    const { categories, items } = window.DATA.menu;
    const [cat, setCat] = useState('all');
    const [q, setQ] = useState('');
    const [vegOnly, setVegOnly] = useState(false);
    const [mode, setMode] = useState('delivery');
    const refs = useRef({});

    const visible = items.filter(i => (!vegOnly || i.veg) && (!q || (i.name + i.desc).toLowerCase().includes(q.toLowerCase())));
    const shownCats = categories.filter(c => visible.some(i => i.cat === c.id));

    const jump = (id) => {
      setCat(id);
      const el = refs.current[id];
      if (el) window.scrollTo({ top: el.offsetTop - 150, behavior: 'smooth' });
    };

    return h('div', { style: { paddingBottom: 100 } },
      h(PageHead, { eyebrow: 'Menu & online ordering', title: 'Order from our kitchen', sub: 'Everything cooked to order. Choose pickup or delivery — we\u2019ll have it ready hot.' }),
      // mode + search bar (sticky)
      h('div', { style: { position: 'sticky', top: 78, zIndex: 40, background: 'rgba(13,10,8,.9)', backdropFilter: 'blur(12px)', borderBottom: '1px solid var(--line)', marginTop: 36 } },
        h('div', { style: { maxWidth: 1240, margin: '0 auto', padding: '16px 32px', display: 'flex', gap: 16, alignItems: 'center', flexWrap: 'wrap' } },
          h('div', { style: { display: 'flex', background: 'var(--ink-700)', border: '1px solid var(--line)', borderRadius: 999, padding: 4 } },
            ['delivery', 'pickup'].map(m => h('button', { key: m, onClick: () => setMode(m), style: { border: 'none', borderRadius: 999, padding: '9px 20px', cursor: 'pointer', fontWeight: 600, fontSize: 14, fontFamily: 'var(--sans)', textTransform: 'capitalize', display: 'flex', alignItems: 'center', gap: 7, background: mode === m ? 'var(--gold-600)' : 'transparent', color: mode === m ? '#211405' : 'var(--cream-2)', transition: 'all .2s' } }, h(Icon, { name: m === 'delivery' ? 'truck' : 'bag', size: 16 }), m))),
          h('div', { style: { flex: 1, minWidth: 200, display: 'flex', alignItems: 'center', gap: 10, background: 'var(--ink-700)', border: '1px solid var(--line)', borderRadius: 999, padding: '0 18px' } },
            h(Icon, { name: 'search', size: 18, color: 'var(--muted)' }),
            h('input', { value: q, onChange: e => setQ(e.target.value), placeholder: 'Search dishes…', style: { flex: 1, background: 'none', border: 'none', outline: 'none', color: 'var(--cream)', fontSize: 15, padding: '12px 0', fontFamily: 'var(--sans)' } })),
          h('button', { onClick: () => setVegOnly(v => !v), style: { display: 'flex', alignItems: 'center', gap: 8, background: vegOnly ? 'rgba(79,125,68,.16)' : 'var(--ink-700)', border: '1px solid ' + (vegOnly ? 'var(--leaf-600)' : 'var(--line)'), borderRadius: 999, padding: '11px 18px', cursor: 'pointer', color: vegOnly ? 'var(--leaf-500)' : 'var(--cream-2)', fontWeight: 600, fontSize: 14, fontFamily: 'var(--sans)' } }, h(Icon, { name: 'veg', size: 14, color: 'var(--leaf-500)' }), 'Veg only')),
        h('div', { style: { maxWidth: 1240, margin: '0 auto', padding: '0 32px 14px', display: 'flex', gap: 8, overflowX: 'auto' } },
          h('button', { onClick: () => jump(shownCats[0]?.id), style: chip(false) }, 'Top'),
          shownCats.map(c => h('button', { key: c.id, onClick: () => jump(c.id), style: chip(false) }, c.name)))),
      // category sections
      h('div', { style: { maxWidth: 1240, margin: '0 auto', padding: '40px 32px 0' } },
        shownCats.map(c => h('div', { key: c.id, ref: el => refs.current[c.id] = el, style: { marginBottom: 56, scrollMarginTop: 150 } },
          h('div', { style: { borderBottom: '1px solid var(--line)', paddingBottom: 16, marginBottom: 26 } },
            h('div', { style: { display: 'flex', alignItems: 'baseline', gap: 14, flexWrap: 'wrap' } },
              h('h2', { style: { fontSize: 34 } }, c.name),
              h('span', { className: 'eyebrow' }, c.tag)),
            h('p', { style: { color: 'var(--muted)', fontSize: 15, marginTop: 6 } }, c.desc)),
          h('div', { className: 'cust-order-grid', style: { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 18 } },
            visible.filter(i => i.cat === c.id).map(it => h(Row, { key: it.id, it, addItem }))))))
    );
  }
  const chip = () => ({ flexShrink: 0, background: 'var(--ink-700)', border: '1px solid var(--line)', borderRadius: 999, padding: '8px 16px', cursor: 'pointer', color: 'var(--cream-2)', fontSize: 13.5, fontWeight: 600, fontFamily: 'var(--sans)', whiteSpace: 'nowrap' });

  function Row({ it, addItem }) {
    const [added, setAdded] = useState(false);
    const onAdd = () => { addItem(it); setAdded(true); setTimeout(() => setAdded(false), 1100); };
    const heat = n => h('span', { style: { display: 'inline-flex', gap: 1, marginLeft: 6, verticalAlign: 'middle' } }, Array.from({ length: n }).map((_, i) => h(Icon, { key: i, name: 'flame', size: 11, color: 'var(--spice-500)' })));
    return h('div', { style: { display: 'flex', gap: 16, background: 'var(--ink-700)', border: '1px solid var(--line)', borderRadius: 14, padding: 14, transition: 'border-color .2s' }, onMouseEnter: e => e.currentTarget.style.borderColor = 'var(--ink-600)', onMouseLeave: e => e.currentTarget.style.borderColor = 'var(--line)' },
      h(Ph, { label: it.img, style: { width: 96, height: 96, flexShrink: 0 }, r: 10 }),
      h('div', { style: { flex: 1, minWidth: 0, display: 'flex', flexDirection: 'column' } },
        h('div', { style: { display: 'flex', justifyContent: 'space-between', gap: 10, alignItems: 'baseline' } },
          h('h4', { style: { fontSize: 18.5, display: 'flex', alignItems: 'center', gap: 7 } }, it.veg && h(Icon, { name: 'veg', size: 12, color: 'var(--leaf-500)' }), it.name, it.spice > 1 && heat(it.spice)),
          h('span', { style: { color: 'var(--gold-400)', fontWeight: 600, fontFamily: 'var(--serif)', fontSize: 19 } }, '$' + it.price)),
        h('p', { style: { color: 'var(--muted)', fontSize: 13.5, lineHeight: 1.5, margin: '6px 0 0' } }, it.desc),
        h('div', { style: { marginTop: 'auto', paddingTop: 12, display: 'flex', alignItems: 'center', gap: 10 } },
          it.popular && h('span', { style: { fontSize: 11, fontWeight: 700, letterSpacing: '.08em', textTransform: 'uppercase', color: 'var(--gold-500)' } }, '★ Popular'),
          h('button', { className: 'btn ' + (added ? 'btn-spice' : 'btn-gold') + ' btn-sm', style: { marginLeft: 'auto' }, onClick: onAdd }, h(Icon, { name: added ? 'check' : 'plus', size: 15 }), added ? 'Added' : 'Add'))));
  }

  // ---------------- Checkout ----------------
  function Checkout() {
    const { cart, subtotal, go, clearCart } = useContext(window.ShopContext);
    const [mode, setMode] = useState('delivery');
    const [tip, setTip] = useState(0.18);
    const [done, setDone] = useState(false);
    const tax = subtotal * 0.0875;
    const fee = mode === 'delivery' ? (subtotal >= 40 ? 0 : 3.99) : 0;
    const tipAmt = subtotal * tip;
    const total = subtotal + tax + fee + tipAmt;

    if (done) return h(Confirmed, { go, clearCart, mode });
    if (cart.length === 0) return h('div', { style: { minHeight: '70vh', display: 'grid', placeItems: 'center', textAlign: 'center', color: 'var(--muted)' } }, h('div', null, h(Icon, { name: 'bag', size: 44, style: { opacity: .4 } }), h('p', { style: { marginTop: 16, fontSize: 18 } }, 'Your bag is empty.'), h('button', { className: 'btn btn-gold', style: { marginTop: 14 }, onClick: () => go('order') }, 'Browse the menu')));

    const field = (label, ph, full, type = 'text') => h('label', { style: { display: 'flex', flexDirection: 'column', gap: 7, gridColumn: full ? '1 / -1' : 'auto' } },
      h('span', { style: { fontSize: 13, color: 'var(--sand)', fontWeight: 600 } }, label),
      h('input', { type, placeholder: ph, style: inp }));

    return h('div', { style: { paddingTop: 120, paddingBottom: 100, maxWidth: 1100, margin: '0 auto', padding: '120px 32px 100px' } },
      h('button', { className: 'btn btn-ghost btn-sm', style: { marginBottom: 22 }, onClick: () => go('order') }, h(Icon, { name: 'arrowL', size: 16 }), 'Back to menu'),
      h('h1', { style: { fontSize: 44, marginBottom: 28 } }, 'Checkout'),
      h('div', { className: 'cust-checkout-grid', style: { display: 'grid', gridTemplateColumns: '1.5fr 1fr', gap: 32, alignItems: 'start' } },
        h('div', { style: { display: 'flex', flexDirection: 'column', gap: 24 } },
          // mode
          h('div', { style: card },
            h('div', { style: { display: 'flex', gap: 12 } }, ['delivery', 'pickup'].map(m => h('button', { key: m, onClick: () => setMode(m), style: { flex: 1, padding: 16, borderRadius: 12, cursor: 'pointer', textAlign: 'left', background: mode === m ? 'var(--gold-glow)' : 'var(--ink-800)', border: '1px solid ' + (mode === m ? 'var(--gold-600)' : 'var(--line)'), color: 'var(--cream)' } },
              h('div', { style: { display: 'flex', alignItems: 'center', gap: 9, fontWeight: 700, marginBottom: 4 } }, h(Icon, { name: m === 'delivery' ? 'truck' : 'bag', size: 18, color: 'var(--gold-400)' }), m === 'delivery' ? 'Delivery' : 'Pickup'),
              h('div', { style: { fontSize: 13, color: 'var(--muted)' } }, m === 'delivery' ? '35–45 min · to your door' : '20–25 min · 418 Saffron Lane'))))),
          h('div', { style: card },
            h('h3', { style: { fontSize: 22, marginBottom: 18 } }, 'Contact details'),
            h('div', { style: { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 14 } }, field('Full name', 'Asha Gurung'), field('Phone', '(415) 555-0140', false, 'tel'), field('Email', 'you@email.com', true, 'email'), mode === 'delivery' && field('Delivery address', '418 Saffron Lane, Apt 2', true), mode === 'delivery' && field('Delivery notes', 'Gate code, floor…', true))),
          h('div', { style: card },
            h('h3', { style: { fontSize: 22, marginBottom: 18 } }, 'Payment'),
            h('div', { style: { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 14 } }, field('Card number', '4242 4242 4242 4242', true), field('Expiry', 'MM / YY'), field('CVC', '123')))),
        // summary
        h('div', { style: { position: 'sticky', top: 100, ...card } },
          h('h3', { style: { fontSize: 22, marginBottom: 16 } }, 'Order summary'),
          h('div', { style: { display: 'flex', flexDirection: 'column', gap: 10, maxHeight: 220, overflowY: 'auto', paddingBottom: 14, borderBottom: '1px solid var(--line)' } },
            cart.map(it => h('div', { key: it.id, style: { display: 'flex', justifyContent: 'space-between', fontSize: 14.5, gap: 10 } }, h('span', { style: { color: 'var(--cream-2)' } }, h('span', { style: { color: 'var(--gold-500)', fontWeight: 700 } }, it.qty + '× '), it.name), h('span', { style: { color: 'var(--cream)' } }, '$' + (it.price * it.qty).toFixed(2))))),
          h('div', { style: { padding: '16px 0', borderBottom: '1px solid var(--line)' } },
            h('div', { style: { fontSize: 13, color: 'var(--sand)', fontWeight: 600, marginBottom: 10 } }, 'Add a tip'),
            h('div', { style: { display: 'flex', gap: 8 } }, [0.15, 0.18, 0.2, 0].map(t => h('button', { key: t, onClick: () => setTip(t), style: { flex: 1, padding: '10px 0', borderRadius: 9, cursor: 'pointer', fontWeight: 600, fontSize: 14, background: tip === t ? 'var(--gold-600)' : 'var(--ink-800)', color: tip === t ? '#211405' : 'var(--cream-2)', border: '1px solid ' + (tip === t ? 'var(--gold-600)' : 'var(--line)'), fontFamily: 'var(--sans)' } }, t === 0 ? 'None' : (t * 100) + '%')))),
          h('div', { style: { padding: '16px 0', display: 'flex', flexDirection: 'column', gap: 9 } },
            sumRow('Subtotal', subtotal), sumRow('Tax', tax), mode === 'delivery' && sumRow('Delivery', fee, fee === 0 ? 'Free' : null), tip > 0 && sumRow('Tip', tipAmt)),
          h('div', { style: { display: 'flex', justifyContent: 'space-between', fontFamily: 'var(--serif)', fontSize: 24, fontWeight: 700, padding: '14px 0', borderTop: '1px solid var(--line)' } }, h('span', null, 'Total'), h('span', { style: { color: 'var(--gold-400)' } }, '$' + total.toFixed(2))),
          h('button', { className: 'btn btn-gold', style: { width: '100%', justifyContent: 'center' }, onClick: () => { setDone(true); window.scrollTo({ top: 0 }); } }, 'Place order · $' + total.toFixed(2))))
    );
  }
  const sumRow = (label, amt, override) => h('div', { style: { display: 'flex', justifyContent: 'space-between', fontSize: 14.5, color: 'var(--sand)' } }, h('span', null, label), h('span', { style: { color: override ? 'var(--leaf-500)' : 'var(--cream-2)' } }, override || ('$' + amt.toFixed(2))));

  function Confirmed({ go, clearCart, mode }) {
    return h('div', { style: { minHeight: '80vh', display: 'grid', placeItems: 'center', padding: '120px 24px 60px', textAlign: 'center' } },
      h('div', { className: 'fade-up', style: { maxWidth: 480 } },
        h('div', { style: { width: 88, height: 88, borderRadius: 999, margin: '0 auto 26px', background: 'var(--gold-glow)', border: '1px solid var(--gold-600)', display: 'grid', placeItems: 'center', color: 'var(--gold-400)' } }, h(Icon, { name: 'check', size: 42 })),
        h('h1', { style: { fontSize: 46 } }, 'Order confirmed'),
        h('p', { style: { color: 'var(--sand)', fontSize: 17, lineHeight: 1.65, marginTop: 16 } }, 'Thank you! Our kitchen is firing up your order. We\u2019ll text you when it\u2019s ' + (mode === 'delivery' ? 'on the way' : 'ready for pickup') + '.'),
        h('div', { style: { display: 'inline-flex', alignItems: 'center', gap: 10, marginTop: 22, background: 'var(--ink-700)', border: '1px solid var(--line)', borderRadius: 999, padding: '12px 22px' } }, h(Icon, { name: 'clock', size: 18, color: 'var(--gold-400)' }), h('span', { style: { fontWeight: 600 } }, mode === 'delivery' ? 'Est. arrival 35–45 min' : 'Ready in 20–25 min'), h('span', { style: { color: 'var(--muted)' } }, '· Order #NK-4821')),
        h('div', { style: { marginTop: 32 } }, h('button', { className: 'btn btn-gold', onClick: () => { clearCart(); go('home'); } }, 'Back to home'))));
  }

  const card = { background: 'var(--ink-700)', border: '1px solid var(--line)', borderRadius: 18, padding: 26 };
  const inp = { background: 'var(--ink-800)', border: '1px solid var(--line)', borderRadius: 10, padding: '13px 15px', color: 'var(--cream)', fontSize: 15, fontFamily: 'var(--sans)', outline: 'none' };

  window.Order = Order; window.Checkout = Checkout; window.PageHead = PageHead;
})();

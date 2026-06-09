/* Customer shell: ShopContext, Nav, Footer, CartDrawer → window */
(function () {
  const { useState, useEffect, useContext, useRef } = React;
  const h = React.createElement;
  const Icon = window.Icon, Logo = window.Logo;

  const ShopContext = React.createContext(null);
  window.ShopContext = ShopContext;

  const NAV = [
    { id: 'home', label: 'Home' },
    { id: 'order', label: 'Menu', header: true },
    { id: 'reserve', label: 'Reserve' },
    { id: 'catering', label: 'Catering', header: true },
    { id: 'gallery', label: 'Gallery', header: true },
    { id: 'about', label: 'About', header: true },
    { id: 'giftcards', label: 'Gift Cards', header: true },
    { id: 'promos', label: 'Offers' },
    { id: 'contact', label: 'Contact', header: true },
  ];

  function Nav() {
    const { view, go, cart, openCart } = useContext(ShopContext);
    const [solid, setSolid] = useState(false);
    const [open, setOpen] = useState(false);
    const count = cart.reduce((n, i) => n + i.qty, 0);
    useEffect(() => {
      const onScroll = () => setSolid(window.scrollY > 30 || view !== 'home');
      onScroll();
      window.addEventListener('scroll', onScroll, { passive: true });
      return () => window.removeEventListener('scroll', onScroll);
    }, [view]);

    return h(React.Fragment, null,
      h('header', {
        style: {
          position: 'fixed', top: 0, left: 0, right: 0, zIndex: 100,
          transition: 'background .3s, border-color .3s, backdrop-filter .3s, box-shadow .3s',
          background: solid ? 'rgba(13,10,8,.88)' : 'transparent',
          backdropFilter: solid ? 'blur(14px)' : 'none',
          borderBottom: '1px solid ' + (solid ? 'var(--line)' : 'transparent'),
          boxShadow: solid ? '0 10px 40px rgba(0,0,0,.35)' : 'none',
        }
      },
        h('div', { style: { maxWidth: 1240, margin: '0 auto', padding: '0 32px', height: 78, display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 24 } },
          h(Logo, { size: 34, onClick: () => go('home') }),
          h('nav', { className: 'cust-navlinks', style: { display: 'flex', gap: 4, alignItems: 'center' } },
            NAV.filter(n => n.header).map(n => h('button', {
              key: n.id, onClick: () => go(n.id),
              style: {
                background: 'none', border: 'none', cursor: 'pointer', font: 'inherit',
                fontSize: 14.5, fontWeight: 500, padding: '8px 14px', borderRadius: 8,
                color: view === n.id ? 'var(--gold-400)' : 'var(--cream-2)', transition: 'color .2s',
              },
              onMouseEnter: e => e.currentTarget.style.color = 'var(--gold-400)',
              onMouseLeave: e => e.currentTarget.style.color = view === n.id ? 'var(--gold-400)' : 'var(--cream-2)',
            }, n.label))
          ),
          h('div', { style: { display: 'flex', alignItems: 'center', gap: 12 } },
            h('button', { onClick: openCart, 'aria-label': 'Cart', style: { position: 'relative', background: 'none', border: '1px solid var(--line)', borderRadius: 999, width: 44, height: 44, display: 'grid', placeItems: 'center', cursor: 'pointer', color: 'var(--cream)' } },
              h(Icon, { name: 'bag', size: 19 }),
              count > 0 && h('span', { style: { position: 'absolute', top: -5, right: -5, background: 'var(--spice-600)', color: '#fff', fontSize: 11, fontWeight: 700, minWidth: 19, height: 19, borderRadius: 999, display: 'grid', placeItems: 'center', padding: '0 5px', fontFamily: 'var(--sans)' } }, count))
            ,
            h('button', { className: 'cust-reserve-btn btn btn-ghost btn-sm', onClick: () => go('reserve') }, 'Reserve'),
            h('button', { className: 'btn btn-gold btn-sm', onClick: () => go('order') }, 'Order Online'),
            h('button', { className: 'cust-burger', onClick: () => setOpen(true), 'aria-label': 'Menu', style: { display: 'none', background: 'none', border: '1px solid var(--line)', borderRadius: 10, width: 44, height: 44, placeItems: 'center', cursor: 'pointer', color: 'var(--cream)' } }, h(Icon, { name: 'menu', size: 20 }))
          )
        )
      ),
      // mobile sheet
      open && h('div', { onClick: () => setOpen(false), style: { position: 'fixed', inset: 0, zIndex: 200, background: 'rgba(0,0,0,.6)', backdropFilter: 'blur(6px)' } },
        h('div', { onClick: e => e.stopPropagation(), style: { position: 'absolute', top: 0, right: 0, bottom: 0, width: 'min(340px, 86vw)', background: 'var(--ink-750)', borderLeft: '1px solid var(--line)', padding: 24, display: 'flex', flexDirection: 'column', gap: 6 } },
          h('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 18 } }, h(Logo, { size: 30 }), h('button', { onClick: () => setOpen(false), style: { background: 'none', border: 'none', color: 'var(--cream)', cursor: 'pointer' } }, h(Icon, { name: 'x', size: 24 }))),
          NAV.map(n => h('button', { key: n.id, onClick: () => { go(n.id); setOpen(false); }, style: { textAlign: 'left', background: 'none', border: 'none', borderBottom: '1px solid var(--line-soft)', padding: '15px 4px', fontSize: 18, fontFamily: 'var(--serif)', color: view === n.id ? 'var(--gold-400)' : 'var(--cream)', cursor: 'pointer' } }, n.label)),
          h('button', { className: 'btn btn-gold', style: { marginTop: 18 }, onClick: () => { go('order'); setOpen(false); } }, 'Order Online')
        )
      )
    );
  }

  function Footer() {
    const { go } = useContext(ShopContext);
    const col = (title, links) => h('div', null,
      h('div', { style: { fontSize: 12, fontWeight: 700, letterSpacing: '.18em', textTransform: 'uppercase', color: 'var(--gold-600)', marginBottom: 16 } }, title),
      h('div', { style: { display: 'flex', flexDirection: 'column', gap: 11 } },
        links.map((l, i) => h('button', { key: i, onClick: l[1], style: { textAlign: 'left', background: 'none', border: 'none', padding: 0, color: 'var(--sand)', fontSize: 14.5, cursor: 'pointer', fontFamily: 'var(--sans)', transition: 'color .2s' }, onMouseEnter: e => e.currentTarget.style.color = 'var(--cream)', onMouseLeave: e => e.currentTarget.style.color = 'var(--sand)' }, l[0])))
    );
    return h('footer', { style: { background: 'var(--ink-900)', borderTop: '1px solid var(--line)', marginTop: 0 } },
      h('div', { style: { maxWidth: 1240, margin: '0 auto', padding: '72px 32px 40px' } },
        h('div', { className: 'cust-foot-grid', style: { display: 'grid', gridTemplateColumns: '1.6fr 1fr 1fr 1.2fr', gap: 48, paddingBottom: 48, borderBottom: '1px solid var(--line)' } },
          h('div', null,
            h(Logo, { size: 36 }),
            h('p', { style: { color: 'var(--sand)', fontSize: 14.5, lineHeight: 1.7, marginTop: 20, maxWidth: 300 } }, 'Hand-pleated momo, charcoal tandoor, and the slow-simmered curries of the Indian subcontinent and the Himalayan hills.'),
            h('div', { style: { display: 'flex', gap: 10, marginTop: 22 } },
              ['ig', 'fb', 'wa'].map(s => h('a', { key: s, href: '#', onClick: e => e.preventDefault(), style: { width: 40, height: 40, borderRadius: 999, border: '1px solid var(--line)', display: 'grid', placeItems: 'center', color: 'var(--cream-2)' } }, h(Icon, { name: s, size: 18 })))),
          ),
          col('Explore', [['Menu & Order', () => go('order')], ['Reserve a Table', () => go('reserve')], ['Catering', () => go('catering')], ['Gallery', () => go('gallery')], ['Gift Cards', () => go('giftcards')], ['Offers', () => go('promos')]]),
          col('Visit', [['About Us', () => go('about')], ['Hours & Location', () => go('contact')], ['Private Dining', () => go('catering')], ['Careers', () => go('contact')]]),
          h('div', null,
            h('div', { style: { fontSize: 12, fontWeight: 700, letterSpacing: '.18em', textTransform: 'uppercase', color: 'var(--gold-600)', marginBottom: 16 } }, 'Find Us'),
            h('div', { style: { color: 'var(--sand)', fontSize: 14.5, lineHeight: 1.8 } },
              h('div', null, '418 Saffron Lane'), h('div', null, 'Riverside District'), h('div', { style: { marginTop: 10, color: 'var(--cream-2)' } }, '(415) 555-0192'),
              h('div', { style: { marginTop: 14, color: 'var(--cream)' } }, 'Tue–Sun · 11:30 – 22:00'), h('div', { style: { color: 'var(--muted)' } }, 'Closed Mondays')),
          )
        ),
        h('div', { style: { display: 'flex', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12, paddingTop: 26, color: 'var(--muted)', fontSize: 13 } },
          h('div', null, '© 2026 Indian Nepali Kitchen. All rights reserved.'),
          h('div', { style: { display: 'flex', gap: 22 } }, h('span', null, 'Privacy'), h('span', null, 'Terms'), h('span', null, 'Accessibility')))
      )
    );
  }

  function CartDrawer() {
    const { cart, cartOpen, closeCart, setQty, removeItem, subtotal, go } = useContext(ShopContext);
    return h('div', { 'aria-hidden': !cartOpen, style: { position: 'fixed', inset: 0, zIndex: 300, pointerEvents: cartOpen ? 'auto' : 'none' } },
      h('div', { onClick: closeCart, style: { position: 'absolute', inset: 0, background: 'rgba(0,0,0,.55)', opacity: cartOpen ? 1 : 0, transition: 'opacity .3s', backdropFilter: cartOpen ? 'blur(4px)' : 'none' } }),
      h('div', { style: { position: 'absolute', top: 0, right: 0, bottom: 0, width: 'min(420px, 92vw)', background: 'var(--ink-750)', borderLeft: '1px solid var(--line)', transform: cartOpen ? 'none' : 'translateX(100%)', transition: 'transform .35s cubic-bezier(.3,.8,.3,1)', display: 'flex', flexDirection: 'column', boxShadow: '-30px 0 60px rgba(0,0,0,.5)' } },
        h('div', { style: { padding: '22px 24px', borderBottom: '1px solid var(--line)', display: 'flex', justifyContent: 'space-between', alignItems: 'center' } },
          h('div', { style: { fontFamily: 'var(--serif)', fontSize: 24, fontWeight: 600 } }, 'Your Order'),
          h('button', { onClick: closeCart, style: { background: 'none', border: 'none', color: 'var(--sand)', cursor: 'pointer' } }, h(Icon, { name: 'x', size: 22 }))),
        cart.length === 0
          ? h('div', { style: { flex: 1, display: 'grid', placeItems: 'center', padding: 40, textAlign: 'center', color: 'var(--muted)' } },
            h('div', null, h(Icon, { name: 'bag', size: 40, style: { opacity: .4 } }), h('p', { style: { marginTop: 14 } }, 'Your bag is empty.'), h('button', { className: 'btn btn-ghost btn-sm', style: { marginTop: 8 }, onClick: () => { closeCart(); go('order'); } }, 'Browse the menu')))
          : h(React.Fragment, null,
            h('div', { style: { flex: 1, overflowY: 'auto', padding: '8px 24px' } },
              cart.map(it => h('div', { key: it.id, style: { display: 'flex', gap: 14, padding: '18px 0', borderBottom: '1px solid var(--line-soft)' } },
                h(window.Ph, { label: it.img, style: { width: 62, height: 62, flexShrink: 0 }, r: 10 }),
                h('div', { style: { flex: 1, minWidth: 0 } },
                  h('div', { style: { display: 'flex', justifyContent: 'space-between', gap: 8 } }, h('div', { style: { fontWeight: 600, fontSize: 15 } }, it.name), h('div', { style: { color: 'var(--gold-400)', fontWeight: 600 } }, '$' + (it.price * it.qty))),
                  h('div', { style: { display: 'flex', alignItems: 'center', gap: 10, marginTop: 10 } },
                    h('div', { style: { display: 'flex', alignItems: 'center', border: '1px solid var(--line)', borderRadius: 999 } },
                      h('button', { onClick: () => setQty(it.id, it.qty - 1), style: qbtn }, h(Icon, { name: 'minus', size: 14 })),
                      h('span', { style: { minWidth: 22, textAlign: 'center', fontWeight: 600, fontSize: 14 } }, it.qty),
                      h('button', { onClick: () => setQty(it.id, it.qty + 1), style: qbtn }, h(Icon, { name: 'plus', size: 14 }))),
                    h('button', { onClick: () => removeItem(it.id), style: { marginLeft: 'auto', background: 'none', border: 'none', color: 'var(--muted)', cursor: 'pointer', display: 'grid', placeItems: 'center' } }, h(Icon, { name: 'trash', size: 16 }))))
              ))
            ),
            h('div', { style: { padding: 24, borderTop: '1px solid var(--line)', background: 'var(--ink-800)' } },
              h('div', { style: { display: 'flex', justifyContent: 'space-between', fontSize: 14, color: 'var(--sand)', marginBottom: 8 } }, h('span', null, 'Subtotal'), h('span', null, '$' + subtotal.toFixed(2))),
              h('div', { style: { display: 'flex', justifyContent: 'space-between', fontSize: 14, color: 'var(--sand)', marginBottom: 14 } }, h('span', null, 'Est. tax'), h('span', null, '$' + (subtotal * .0875).toFixed(2))),
              h('div', { style: { display: 'flex', justifyContent: 'space-between', fontSize: 19, fontWeight: 700, marginBottom: 18, fontFamily: 'var(--serif)' } }, h('span', null, 'Total'), h('span', { style: { color: 'var(--gold-400)' } }, '$' + (subtotal * 1.0875).toFixed(2))),
              h('button', { className: 'btn btn-gold', style: { width: '100%', justifyContent: 'center' }, onClick: () => { closeCart(); go('checkout'); } }, 'Checkout', h(Icon, { name: 'arrow', size: 18 }))))
      )
    );
  }
  const qbtn = { background: 'none', border: 'none', color: 'var(--cream)', cursor: 'pointer', padding: '7px 9px', display: 'grid', placeItems: 'center' };

  window.Nav = Nav; window.Footer = Footer; window.CartDrawer = CartDrawer;
})();

/* Customer homepage → window.Home */
(function () {
  const { useContext, useState, useEffect, useRef } = React;
  const h = React.createElement;
  const Icon = window.Icon, Ph = window.Ph, Stars = window.Stars;

  const Section = ({ children, style, ...rest }) => h('section', { style: { maxWidth: 1240, margin: '0 auto', padding: '0 32px', ...style }, ...rest }, children);

  function Hero() {
    const { go } = useContext(window.ShopContext);
    return h('div', { style: { position: 'relative', minHeight: '100vh', display: 'flex', alignItems: 'center', overflow: 'hidden' } },
      // bg placeholder image
      h('div', { className: 'ph', style: { position: 'absolute', inset: 0, border: 'none', borderRadius: 0 } }, h('span', { style: { position: 'absolute', bottom: 24, right: 24 } }, 'hero photo — tandoor & thali, dim warm light')),
      h('div', { style: { position: 'absolute', inset: 0, background: 'linear-gradient(105deg, rgba(13,10,8,.94) 0%, rgba(13,10,8,.7) 45%, rgba(13,10,8,.35) 100%)' } }),
      h('div', { style: { position: 'absolute', inset: 0, background: 'radial-gradient(120% 90% at 80% 110%, var(--gold-glow), transparent 60%)' } }),
      h(Section, { style: { position: 'relative', zIndex: 2, paddingTop: 100, paddingBottom: 60 } },
        h('div', { className: 'fade-up', style: { maxWidth: 720 } },
          h('div', { className: 'eyebrow', style: { marginBottom: 24 } }, 'Indian & Nepali · Est. in the Riverside District'),
          h('h1', { style: { fontSize: 'clamp(46px, 7vw, 92px)', lineHeight: .98, fontWeight: 600, letterSpacing: '-.01em' } },
            'Where the ', h('em', { style: { fontStyle: 'italic', color: 'var(--gold-400)' } }, 'Himalayas'), h('br'), 'meet the tandoor.'),
          h('p', { style: { fontSize: 'clamp(17px,2vw,20px)', lineHeight: 1.6, color: 'var(--cream-2)', maxWidth: 540, marginTop: 26 } },
            'Hand-pleated momo, charcoal-fired kebabs, and curries ground fresh each morning — served in a warm, candle-lit room.'),
          h('div', { style: { display: 'flex', gap: 14, marginTop: 38, flexWrap: 'wrap' } },
            h('button', { className: 'btn btn-gold btn-lg', onClick: () => go('order') }, h(Icon, { name: 'bag', size: 19 }), 'Order Online'),
            h('button', { className: 'btn btn-ghost btn-lg', onClick: () => go('reserve') }, h(Icon, { name: 'cal', size: 19 }), 'Reserve a Table')),
          h('div', { style: { display: 'flex', gap: 30, marginTop: 46, flexWrap: 'wrap', alignItems: 'center' } },
            h('div', { style: { display: 'flex', alignItems: 'center', gap: 10 } }, h(Stars, { value: 5, size: 16 }), h('span', { style: { fontSize: 14, color: 'var(--sand)' } }, '4.9 · 1,200+ reviews')),
            h('div', { style: { width: 1, height: 26, background: 'var(--line)' } }),
            h('div', { style: { display: 'flex', alignItems: 'center', gap: 9, fontSize: 14, color: 'var(--sand)' } }, h(Icon, { name: 'clock', size: 16, color: 'var(--gold-500)' }), 'Open today till 10pm'),
            h('div', { style: { width: 1, height: 26, background: 'var(--line)' } }),
            h('div', { style: { display: 'flex', alignItems: 'center', gap: 9, fontSize: 14, color: 'var(--sand)' } }, h(Icon, { name: 'truck', size: 16, color: 'var(--gold-500)' }), 'Delivery in 35–45 min')),
        )
      ),
      h('div', { style: { position: 'absolute', bottom: 26, left: '50%', transform: 'translateX(-50%)', zIndex: 2, color: 'var(--muted)', display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 6, fontSize: 11, letterSpacing: '.2em', textTransform: 'uppercase' } }, 'Scroll', h(Icon, { name: 'down', size: 16 })),
    );
  }

  function Marquee() {
    const words = ['Momo', 'Tandoor', 'Thali', 'Biryani', 'Jhol', 'Sekuwa', 'Naan', 'Masala', 'Sukuti', 'Dal'];
    const row = h('div', { style: { display: 'flex', gap: 52, paddingRight: 52, flexShrink: 0 } },
      words.map((w, i) => h('span', { key: i, style: { fontFamily: 'var(--serif)', fontStyle: 'italic', fontSize: 26, color: 'var(--faint)', display: 'inline-flex', alignItems: 'center', gap: 52 } }, w, h('span', { style: { color: 'var(--gold-700)' } }, '◆'))));
    return h('div', { style: { borderTop: '1px solid var(--line)', borderBottom: '1px solid var(--line)', padding: '22px 0', overflow: 'hidden', background: 'var(--ink-850)' } },
      h('div', { style: { display: 'flex', width: 'max-content', animation: 'marq 32s linear infinite' } }, row, row),
      h('style', null, '@keyframes marq{to{transform:translateX(-50%)}}'));
  }

  function Story() {
    const card = (eyebrow, title, body, tag) => h('div', { style: { display: 'flex', flexDirection: 'column' } },
      h(Ph, { label: tag, h: 360, r: 16, style: { marginBottom: 24 } }),
      h('div', { className: 'eyebrow', style: { marginBottom: 14 } }, eyebrow),
      h('h3', { style: { fontSize: 30, marginBottom: 12 } }, title),
      h('p', { style: { color: 'var(--sand)', fontSize: 16, lineHeight: 1.7 } }, body));
    return h(Section, { style: { padding: '110px 32px' } },
      h('div', { style: { textAlign: 'center', maxWidth: 640, margin: '0 auto 64px' } },
        h('div', { className: 'eyebrow center', style: { justifyContent: 'center', marginBottom: 18 } }, 'Two kitchens, one table'),
        h('h2', { style: { fontSize: 'clamp(34px,4.5vw,54px)', lineHeight: 1.05 } }, 'A menu that travels from Delhi\u2019s lanes to the foothills of Everest'),
        h('p', { style: { color: 'var(--sand)', fontSize: 17, lineHeight: 1.7, marginTop: 20 } }, 'Our chefs trained on both sides of the border. Every plate honors the technique, spice, and generosity of its home.')),
      h('div', { className: 'cust-story-grid', style: { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 40 } },
        card('The Subcontinent', 'Tandoor & Curry', 'Overnight marinades fired in a 480°C charcoal tandoor; gravies built from whole spices toasted and ground every morning.', 'Tandoori platter'),
        card('The Himalayas', 'Momo & Sekuwa', 'Dumplings pleated by hand to order, sukuti dried in-house, and the warm sesame jhol that made us a neighborhood ritual.', 'Momo basket')));
  }

  function Signatures() {
    const { go, addItem } = useContext(window.ShopContext);
    const items = window.DATA.menu.items.filter(i => i.popular).slice(0, 6);
    return h('div', { style: { background: 'var(--ink-850)', borderTop: '1px solid var(--line)', borderBottom: '1px solid var(--line)', padding: '110px 0' } },
      h(Section, null,
        h('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'flex-end', flexWrap: 'wrap', gap: 20, marginBottom: 52 } },
          h('div', null, h('div', { className: 'eyebrow', style: { marginBottom: 16 } }, 'Most loved'), h('h2', { style: { fontSize: 'clamp(32px,4vw,48px)' } }, 'Signature dishes')),
          h('button', { className: 'btn btn-ghost', onClick: () => go('order') }, 'See full menu', h(Icon, { name: 'arrow', size: 18 }))),
        h('div', { className: 'cust-dish-grid', style: { display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: 26 } },
          items.map(it => h(DishCard, { key: it.id, it, addItem, go }))))
    );
  }

  function DishCard({ it, addItem, go }) {
    const [hover, setHover] = useState(false);
    return h('div', { onMouseEnter: () => setHover(true), onMouseLeave: () => setHover(false),
      style: { background: 'var(--ink-700)', border: '1px solid var(--line)', borderRadius: 18, overflow: 'hidden', transition: 'transform .25s, box-shadow .25s, border-color .25s', transform: hover ? 'translateY(-5px)' : 'none', boxShadow: hover ? 'var(--shadow-2)' : 'none', borderColor: hover ? 'var(--ink-600)' : 'var(--line)' } },
      h('div', { style: { position: 'relative' } },
        h(Ph, { label: it.img, h: 200 }),
        it.popular && h('div', { style: { position: 'absolute', top: 14, left: 14, background: 'rgba(13,10,8,.7)', backdropFilter: 'blur(6px)', border: '1px solid var(--gold-700)', color: 'var(--gold-400)', fontSize: 11, fontWeight: 700, letterSpacing: '.1em', textTransform: 'uppercase', padding: '6px 11px', borderRadius: 999 } }, '★ Popular')),
      h('div', { style: { padding: 22 } },
        h('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'baseline', gap: 12 } },
          h('h4', { style: { fontSize: 21, display: 'flex', alignItems: 'center', gap: 8 } }, it.veg && h(Icon, { name: 'veg', size: 13, color: 'var(--leaf-500)' }), it.name),
          h('span', { style: { color: 'var(--gold-400)', fontWeight: 600, fontFamily: 'var(--serif)', fontSize: 21 } }, '$' + it.price)),
        h('p', { style: { color: 'var(--muted)', fontSize: 14, lineHeight: 1.55, marginTop: 8, minHeight: 44 } }, it.desc),
        h('button', { className: 'btn btn-gold btn-sm', style: { marginTop: 16, width: '100%', justifyContent: 'center' }, onClick: () => addItem(it) }, h(Icon, { name: 'plus', size: 16 }), 'Add to order')));
  }

  function Bands() {
    const { go } = useContext(window.ShopContext);
    const band = (rev, eyebrow, title, body, btn, action, tag, icon) => h('div', { className: 'cust-band', style: { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 56, alignItems: 'center' } },
      h('div', { style: { order: rev ? 2 : 1 } },
        h('div', { className: 'eyebrow', style: { marginBottom: 16 } }, eyebrow),
        h('h2', { style: { fontSize: 'clamp(30px,3.6vw,44px)', lineHeight: 1.06 } }, title),
        h('p', { style: { color: 'var(--sand)', fontSize: 16.5, lineHeight: 1.7, marginTop: 18, maxWidth: 440 } }, body),
        h('button', { className: 'btn btn-gold', style: { marginTop: 28 }, onClick: action }, h(Icon, { name: icon, size: 18 }), btn)),
      h(Ph, { label: tag, h: 380, r: 18, style: { order: rev ? 1 : 2 } }));
    return h(Section, { style: { padding: '110px 32px', display: 'flex', flexDirection: 'column', gap: 110 } },
      band(false, 'Order online', 'Delivery & pickup, ready when you are', 'Build your order in minutes — fresh from our kitchen, packed to travel, tracked from pan to door. Free delivery over $40 within 4 miles.', 'Start an order', () => go('order'), 'Packed delivery bags', 'bag'),
      band(true, 'Reservations', 'Save your table for the evening', 'Book a candle-lit table for two or a long table for the whole family. Instant confirmation, special-occasion notes, and a hold on our chef\u2019s tasting counter.', 'Reserve a table', () => go('reserve'), 'Candle-lit dining room', 'cal'));
  }

  function Catering() {
    const { go } = useContext(window.ShopContext);
    const feat = [['box', 'Family-size trays', 'Half & full pans of every curry, biryani and momo'], ['users', 'Events 20–300', 'Weddings, office lunches, pujas & celebrations'], ['fork', 'Live momo station', 'A chef pleating & steaming on-site, on request']];
    return h('div', { style: { position: 'relative', overflow: 'hidden', background: 'var(--ink-900)', borderTop: '1px solid var(--line)' } },
      h('div', { className: 'ph', style: { position: 'absolute', inset: 0, border: 'none' } }, h('span', { style: { position: 'absolute', bottom: 20, right: 24 } }, 'catering spread photo')),
      h('div', { style: { position: 'absolute', inset: 0, background: 'linear-gradient(0deg, rgba(13,10,8,.96), rgba(13,10,8,.82))' } }),
      h(Section, { style: { position: 'relative', zIndex: 2, padding: '110px 32px', textAlign: 'center' } },
        h('div', { className: 'eyebrow center', style: { justifyContent: 'center', marginBottom: 18 } }, 'Catering & events'),
        h('h2', { style: { fontSize: 'clamp(34px,4.6vw,56px)', maxWidth: 760, margin: '0 auto', lineHeight: 1.05 } }, 'Bring the feast to your table'),
        h('p', { style: { color: 'var(--sand)', fontSize: 17, lineHeight: 1.7, maxWidth: 560, margin: '20px auto 0' } }, 'From an intimate dinner party to a 300-guest wedding mandap, our catering team plans, cooks and serves a spread your guests will remember.'),
        h('div', { className: 'cust-cater-grid', style: { display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: 24, margin: '48px 0', textAlign: 'left' } },
          feat.map((f, i) => h('div', { key: i, style: { background: 'var(--ink-700)', border: '1px solid var(--line)', borderRadius: 16, padding: 26 } },
            h('div', { style: { width: 46, height: 46, borderRadius: 12, background: 'var(--gold-glow)', border: '1px solid var(--gold-700)', display: 'grid', placeItems: 'center', color: 'var(--gold-400)', marginBottom: 16 } }, h(Icon, { name: f[0], size: 22 })),
            h('h4', { style: { fontSize: 21, marginBottom: 6 } }, f[1]), h('p', { style: { color: 'var(--muted)', fontSize: 14.5, lineHeight: 1.6 } }, f[2])))),
        h('button', { className: 'btn btn-gold btn-lg', onClick: () => go('catering') }, 'Request a catering quote', h(Icon, { name: 'arrow', size: 18 })))
    );
  }

  function GalleryStrip() {
    const { go } = useContext(window.ShopContext);
    const imgs = window.DATA.gallery.slice(0, 5);
    return h(Section, { style: { padding: '110px 32px' } },
      h('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'flex-end', flexWrap: 'wrap', gap: 16, marginBottom: 44 } },
        h('div', null, h('div', { className: 'eyebrow', style: { marginBottom: 16 } }, 'The room & the food'), h('h2', { style: { fontSize: 'clamp(32px,4vw,48px)' } }, 'A look inside')),
        h('button', { className: 'btn btn-ghost', onClick: () => go('gallery') }, 'Full gallery', h(Icon, { name: 'arrow', size: 18 }))),
      h('div', { style: { display: 'grid', gridTemplateColumns: '2fr 1fr 1fr', gridTemplateRows: '180px 180px', gap: 14 } },
        h(Ph, { label: imgs[0], r: 14, style: { gridRow: '1 / 3' } }),
        h(Ph, { label: imgs[1], r: 14 }), h(Ph, { label: imgs[2], r: 14 }),
        h(Ph, { label: imgs[3], r: 14 }), h(Ph, { label: imgs[4], r: 14 })));
  }

  function Reviews() {
    return h('div', { style: { background: 'var(--ink-850)', borderTop: '1px solid var(--line)', borderBottom: '1px solid var(--line)', padding: '100px 0' } },
      h(Section, null,
        h('div', { style: { textAlign: 'center', marginBottom: 56 } }, h('div', { className: 'eyebrow center', style: { justifyContent: 'center', marginBottom: 16 } }, 'Guest love'), h('h2', { style: { fontSize: 'clamp(32px,4vw,48px)' } }, 'What the neighborhood says')),
        h('div', { className: 'cust-rev-grid', style: { display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: 24 } },
          window.DATA.reviews.map((r, i) => h('div', { key: i, style: { background: 'var(--ink-700)', border: '1px solid var(--line)', borderRadius: 18, padding: 30 } },
            h(Stars, { value: r.stars, size: 16 }),
            h('p', { style: { fontFamily: 'var(--serif)', fontStyle: 'italic', fontSize: 21, lineHeight: 1.45, color: 'var(--cream)', margin: '18px 0 22px' } }, '\u201C' + r.text + '\u201D'),
            h('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center' } }, h('span', { style: { fontWeight: 600 } }, r.name), h('span', { style: { fontSize: 12, color: 'var(--muted)', letterSpacing: '.1em', textTransform: 'uppercase' } }, r.tag))))))
    );
  }

  function ClosingCTA() {
    const { go } = useContext(window.ShopContext);
    return h(Section, { style: { padding: '120px 32px', textAlign: 'center' } },
      h('div', { style: { color: 'var(--gold-600)', fontSize: 40, marginBottom: 10 } }, '◆'),
      h('h2', { style: { fontSize: 'clamp(36px,5vw,64px)', lineHeight: 1.04, maxWidth: 760, margin: '0 auto' } }, 'Pull up a chair. Dinner\u2019s on the fire.'),
      h('div', { style: { display: 'flex', gap: 14, justifyContent: 'center', marginTop: 36, flexWrap: 'wrap' } },
        h('button', { className: 'btn btn-gold btn-lg', onClick: () => go('order') }, h(Icon, { name: 'bag', size: 19 }), 'Order Online'),
        h('button', { className: 'btn btn-ghost btn-lg', onClick: () => go('reserve') }, h(Icon, { name: 'cal', size: 19 }), 'Reserve a Table')));
  }

  function Home() {
    return h('div', null, h(Hero), h(Marquee), h(Story), h(Signatures), h(Bands), h(Catering), h(GalleryStrip), h(Reviews), h(ClosingCTA));
  }
  window.Home = Home;
})();

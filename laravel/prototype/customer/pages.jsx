/* Reserve, Catering, Gallery, Promos, Contact → window */
(function () {
  const { useContext, useState } = React;
  const h = React.createElement;
  const Icon = window.Icon, Ph = window.Ph;
  const PageHead = window.PageHead;
  const card = { background: 'var(--ink-700)', border: '1px solid var(--line)', borderRadius: 18, padding: 28 };
  const inp = { background: 'var(--ink-800)', border: '1px solid var(--line)', borderRadius: 10, padding: '13px 15px', color: 'var(--cream)', fontSize: 15, fontFamily: 'var(--sans)', outline: 'none', width: '100%' };
  const Field = ({ label, children, full }) => h('label', { style: { display: 'flex', flexDirection: 'column', gap: 7, gridColumn: full ? '1 / -1' : 'auto' } }, h('span', { style: { fontSize: 13, color: 'var(--sand)', fontWeight: 600 } }, label), children);
  const Wrap = ({ children, w = 1100 }) => h('div', { style: { maxWidth: w, margin: '0 auto', padding: '0 32px 110px' } }, children);

  // ---------------- Reserve ----------------
  function Reserve() {
    const { go } = useContext(window.ShopContext);
    const [party, setParty] = useState(2);
    const [date, setDate] = useState('');
    const [time, setTime] = useState('');
    const [done, setDone] = useState(false);
    const times = ['11:30', '12:00', '12:30', '13:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00'];
    const dates = Array.from({ length: 8 }).map((_, i) => { const d = new Date(2026, 5, 3 + i); return { v: d.toISOString().slice(0, 10), wd: d.toLocaleDateString('en', { weekday: 'short' }), d: d.getDate(), mo: d.toLocaleDateString('en', { month: 'short' }) }; });

    if (done) return h('div', { style: { minHeight: '80vh', display: 'grid', placeItems: 'center', padding: '120px 24px', textAlign: 'center' } },
      h('div', { className: 'fade-up', style: { maxWidth: 480 } },
        h('div', { style: { width: 88, height: 88, borderRadius: 999, margin: '0 auto 26px', background: 'var(--gold-glow)', border: '1px solid var(--gold-600)', display: 'grid', placeItems: 'center', color: 'var(--gold-400)' } }, h(Icon, { name: 'check', size: 42 })),
        h('h1', { style: { fontSize: 46 } }, 'Table reserved'),
        h('p', { style: { color: 'var(--sand)', fontSize: 17, lineHeight: 1.65, marginTop: 16 } }, 'See you soon! A confirmation is on its way to your inbox.'),
        h('div', { style: { display: 'inline-flex', flexWrap: 'wrap', justifyContent: 'center', gap: 16, marginTop: 24, background: 'var(--ink-700)', border: '1px solid var(--line)', borderRadius: 14, padding: '16px 26px' } },
          h('span', { style: { display: 'flex', gap: 8, alignItems: 'center' } }, h(Icon, { name: 'users', size: 17, color: 'var(--gold-400)' }), party + ' guests'),
          h('span', { style: { display: 'flex', gap: 8, alignItems: 'center' } }, h(Icon, { name: 'cal', size: 17, color: 'var(--gold-400)' }), date || 'Jun 3'),
          h('span', { style: { display: 'flex', gap: 8, alignItems: 'center' } }, h(Icon, { name: 'clock', size: 17, color: 'var(--gold-400)' }), time || '19:00')),
        h('div', { style: { marginTop: 30 } }, h('button', { className: 'btn btn-gold', onClick: () => go('home') }, 'Back to home'))));

    return h('div', null,
      h(PageHead, { eyebrow: 'Reservations', title: 'Reserve your table', sub: 'Book instantly — for an intimate dinner or the whole family. Large parties and the chef\u2019s counter, just ask.' }),
      h('div', { style: { height: 40 } }),
      h(Wrap, { w: 1080 },
        h('div', { className: 'cust-reserve-grid', style: { display: 'grid', gridTemplateColumns: '1.4fr 1fr', gap: 28, alignItems: 'start' } },
          h('div', { style: card },
            h('div', { style: { marginBottom: 24 } },
              h('div', { style: { fontSize: 13, color: 'var(--sand)', fontWeight: 600, marginBottom: 12 } }, 'Party size'),
              h('div', { style: { display: 'flex', gap: 8, flexWrap: 'wrap' } }, [1, 2, 3, 4, 5, 6, 7, 8].map(n => h('button', { key: n, onClick: () => setParty(n), style: pill(party === n, 48) }, n)), h('button', { onClick: () => setParty(9), style: pill(party === 9, 'auto') }, '9+'))),
            h('div', { style: { marginBottom: 24 } },
              h('div', { style: { fontSize: 13, color: 'var(--sand)', fontWeight: 600, marginBottom: 12 } }, 'Date'),
              h('div', { style: { display: 'flex', gap: 8, overflowX: 'auto', paddingBottom: 4 } }, dates.map(d => h('button', { key: d.v, onClick: () => setDate(d.v), style: { flexShrink: 0, width: 64, padding: '12px 0', borderRadius: 12, cursor: 'pointer', textAlign: 'center', background: date === d.v ? 'var(--gold-600)' : 'var(--ink-800)', color: date === d.v ? '#211405' : 'var(--cream)', border: '1px solid ' + (date === d.v ? 'var(--gold-600)' : 'var(--line)') } }, h('div', { style: { fontSize: 11, opacity: .8, textTransform: 'uppercase', letterSpacing: '.06em' } }, d.wd), h('div', { style: { fontSize: 22, fontFamily: 'var(--serif)', fontWeight: 600, lineHeight: 1.1 } }, d.d), h('div', { style: { fontSize: 11, opacity: .7 } }, d.mo))))),
            h('div', null,
              h('div', { style: { fontSize: 13, color: 'var(--sand)', fontWeight: 600, marginBottom: 12 } }, 'Time'),
              h('div', { style: { display: 'grid', gridTemplateColumns: 'repeat(6,1fr)', gap: 8 } }, times.map(t => h('button', { key: t, onClick: () => setTime(t), style: pill(time === t, 'auto', '11px 0') }, t))))),
          h('div', { style: card },
            h('h3', { style: { fontSize: 24, marginBottom: 6 } }, 'Your details'),
            h('p', { style: { color: 'var(--muted)', fontSize: 14, marginBottom: 20 } }, 'We\u2019ll hold your table for 15 minutes.'),
            h('div', { style: { display: 'grid', gap: 14 } },
              h(Field, { label: 'Full name' }, h('input', { style: inp, placeholder: 'Asha Gurung' })),
              h(Field, { label: 'Phone' }, h('input', { style: inp, placeholder: '(415) 555-0140', type: 'tel' })),
              h(Field, { label: 'Email' }, h('input', { style: inp, placeholder: 'you@email.com', type: 'email' })),
              h(Field, { label: 'Occasion (optional)' }, h('select', { style: { ...inp, appearance: 'none' } }, ['—', 'Birthday', 'Anniversary', 'Date night', 'Business', 'Celebration'].map(o => h('option', { key: o, style: { background: '#1d1712' } }, o)))),
              h(Field, { label: 'Special requests' }, h('textarea', { style: { ...inp, minHeight: 70, resize: 'vertical' }, placeholder: 'Window table, high chair, allergies…' }))),
            h('button', { className: 'btn btn-gold', style: { width: '100%', justifyContent: 'center', marginTop: 20 }, onClick: () => { setDone(true); window.scrollTo({ top: 0 }); } }, 'Confirm reservation'))))
    );
  }
  const pill = (active, w, pad = '0') => ({ minWidth: w === 'auto' ? 'auto' : w, width: typeof w === 'number' ? w : 'auto', height: typeof w === 'number' ? 48 : 'auto', padding: pad === '0' ? (typeof w === 'number' ? 0 : '0 16px') : pad, borderRadius: 12, cursor: 'pointer', fontWeight: 600, fontSize: 15, fontFamily: 'var(--sans)', background: active ? 'var(--gold-600)' : 'var(--ink-800)', color: active ? '#211405' : 'var(--cream)', border: '1px solid ' + (active ? 'var(--gold-600)' : 'var(--line)'), display: 'grid', placeItems: 'center' });

  // ---------------- Catering ----------------
  function Catering() {
    const { go } = useContext(window.ShopContext);
    const [done, setDone] = useState(false);
    const pkgs = [
      { name: 'The Gathering', range: '20–40 guests', price: 'from $22/guest', items: ['Choice of 3 curries', 'Two momo varieties', 'Biryani & rice', 'Naan basket & achar', 'One dessert'], pop: false },
      { name: 'The Celebration', range: '40–120 guests', price: 'from $28/guest', items: ['Choice of 5 curries', 'Live momo station', 'Tandoor platter', 'Two biryani', 'Breads, sides & 2 desserts', 'Chafing & service ware'], pop: true },
      { name: 'The Banquet', range: '120–300 guests', price: 'custom', items: ['Full custom menu', 'On-site chefs & servers', 'Live stations', 'Beverage & chai service', 'Setup, service & cleanup', 'Dedicated event lead'], pop: false },
    ];
    if (done) return h('div', { style: { minHeight: '80vh', display: 'grid', placeItems: 'center', padding: '120px 24px', textAlign: 'center' } }, h('div', { className: 'fade-up', style: { maxWidth: 500 } }, h('div', { style: { width: 88, height: 88, borderRadius: 999, margin: '0 auto 26px', background: 'var(--gold-glow)', border: '1px solid var(--gold-600)', display: 'grid', placeItems: 'center', color: 'var(--gold-400)' } }, h(Icon, { name: 'check', size: 42 })), h('h1', { style: { fontSize: 46 } }, 'Inquiry received'), h('p', { style: { color: 'var(--sand)', fontSize: 17, lineHeight: 1.65, marginTop: 16 } }, 'Our events team will reach out within one business day with a tailored proposal and tasting invite.'), h('button', { className: 'btn btn-gold', style: { marginTop: 28 }, onClick: () => go('home') }, 'Back to home')));

    return h('div', null,
      h(PageHead, { eyebrow: 'Catering & private events', title: 'Catering, done generously', sub: 'Weddings, office lunches, pujas and celebrations — we plan, cook and serve a spread to remember.' }),
      h(Wrap, { w: 1200 },
        h('div', { style: { height: 48 } }),
        h('div', { className: 'cust-pkg-grid', style: { display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: 22, marginBottom: 64 } },
          pkgs.map(p => h('div', { key: p.name, style: { ...card, borderColor: p.pop ? 'var(--gold-700)' : 'var(--line)', position: 'relative', display: 'flex', flexDirection: 'column' } },
            p.pop && h('div', { style: { position: 'absolute', top: -12, left: 28, background: 'var(--gold-600)', color: '#211405', fontSize: 11, fontWeight: 700, letterSpacing: '.1em', textTransform: 'uppercase', padding: '5px 12px', borderRadius: 999 } }, 'Most popular'),
            h('h3', { style: { fontSize: 28 } }, p.name),
            h('div', { style: { color: 'var(--muted)', fontSize: 14, margin: '4px 0 14px' } }, p.range),
            h('div', { style: { fontFamily: 'var(--serif)', fontSize: 26, color: 'var(--gold-400)', fontWeight: 600, paddingBottom: 18, borderBottom: '1px solid var(--line)', marginBottom: 18 } }, p.price),
            h('div', { style: { display: 'flex', flexDirection: 'column', gap: 11, flex: 1 } }, p.items.map((it, i) => h('div', { key: i, style: { display: 'flex', gap: 10, fontSize: 14.5, color: 'var(--cream-2)' } }, h(Icon, { name: 'check', size: 16, color: 'var(--gold-500)', style: { marginTop: 2, flexShrink: 0 } }), it))),
            h('button', { className: 'btn ' + (p.pop ? 'btn-gold' : 'btn-ghost'), style: { marginTop: 22, width: '100%', justifyContent: 'center' }, onClick: () => document.getElementById('cater-form')?.scrollIntoView({ behavior: 'smooth' }) }, 'Choose ' + p.name.split(' ')[1]))) ),
        // inquiry form
        h('div', { id: 'cater-form', style: { ...card, scrollMarginTop: 110 } },
          h('h2', { style: { fontSize: 32, marginBottom: 6 } }, 'Request a quote'),
          h('p', { style: { color: 'var(--muted)', fontSize: 15, marginBottom: 24 } }, 'Tell us about your event and we\u2019ll build a menu and proposal for you.'),
          h('div', { style: { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 16 } },
            h(Field, { label: 'Name' }, h('input', { style: inp, placeholder: 'Your name' })),
            h(Field, { label: 'Email' }, h('input', { style: inp, placeholder: 'you@email.com', type: 'email' })),
            h(Field, { label: 'Phone' }, h('input', { style: inp, placeholder: '(415) 555-0140', type: 'tel' })),
            h(Field, { label: 'Event type' }, h('select', { style: { ...inp, appearance: 'none' } }, ['Wedding', 'Corporate', 'Birthday', 'Puja / religious', 'Other'].map(o => h('option', { key: o, style: { background: '#1d1712' } }, o)))),
            h(Field, { label: 'Event date' }, h('input', { style: inp, type: 'date' })),
            h(Field, { label: 'Guest count' }, h('input', { style: inp, type: 'number', placeholder: '60' })),
            h(Field, { label: 'Tell us more', full: true }, h('textarea', { style: { ...inp, minHeight: 90, resize: 'vertical' }, placeholder: 'Venue, dietary needs, service style, budget…' }))),
          h('button', { className: 'btn btn-gold', style: { marginTop: 22 }, onClick: () => { setDone(true); window.scrollTo({ top: 0 }); } }, 'Send inquiry', h(Icon, { name: 'arrow', size: 18 }))))
    );
  }

  // ---------------- Gallery ----------------
  function Gallery() {
    const cats = window.DATA.galleryCats;
    const [tab, setTab] = useState('all');
    const [lb, setLb] = useState(null);
    const spans = [2, 1, 1, 1, 2, 1, 1, 1, 2, 1, 1, 1];
    const shown = tab === 'all' ? cats.flatMap(c => c.items.map(it => ({ label: it, cat: c.name }))) : cats.find(c => c.id === tab).items.map(it => ({ label: it, cat: cats.find(c => c.id === tab).name }));
    const tabs = [{ id: 'all', name: 'All' }, ...cats];
    return h('div', null,
      h(PageHead, { eyebrow: 'Gallery', title: 'A look inside', sub: 'The plates, the room, and the feasts we cater — browse by what you\u2019re after.' }),
      h(Wrap, { w: 1200 },
        h('div', { style: { display: 'flex', justifyContent: 'center', gap: 8, margin: '40px 0 36px', flexWrap: 'wrap' } },
          tabs.map(t => h('button', { key: t.id, onClick: () => setTab(t.id), style: { background: tab === t.id ? 'var(--gold-600)' : 'var(--ink-700)', color: tab === t.id ? '#211405' : 'var(--cream-2)', border: '1px solid ' + (tab === t.id ? 'var(--gold-600)' : 'var(--line)'), borderRadius: 999, padding: '10px 20px', cursor: 'pointer', fontSize: 14, fontWeight: 600, fontFamily: 'var(--sans)' } }, t.name))),
        h('div', { style: { display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gridAutoRows: '230px', gap: 14, gridAutoFlow: 'dense' } },
          shown.map((g, i) => h('button', { key: i, onClick: () => setLb(g.label), style: { padding: 0, border: 'none', cursor: 'pointer', borderRadius: 14, overflow: 'hidden', position: 'relative', gridColumn: spans[i % spans.length] === 2 ? 'span 2' : 'auto' } },
            h(Ph, { label: g.label, style: { width: '100%', height: '100%' } }),
            h('span', { style: { position: 'absolute', left: 12, bottom: 12, fontSize: 10.5, fontWeight: 700, letterSpacing: '.1em', textTransform: 'uppercase', color: 'var(--gold-400)', background: 'rgba(13,10,8,.7)', backdropFilter: 'blur(6px)', padding: '4px 9px', borderRadius: 999, border: '1px solid var(--gold-700)' } }, g.cat)))),
        lb && h('div', { onClick: () => setLb(null), style: { position: 'fixed', inset: 0, zIndex: 400, background: 'rgba(0,0,0,.85)', backdropFilter: 'blur(8px)', display: 'grid', placeItems: 'center', padding: 40 } },
          h('div', { style: { position: 'relative', width: 'min(900px,90vw)', height: 'min(600px,80vh)' } }, h(Ph, { label: lb, style: { width: '100%', height: '100%' }, r: 16 }), h('button', { onClick: () => setLb(null), style: { position: 'absolute', top: -50, right: 0, background: 'none', border: 'none', color: '#fff', cursor: 'pointer' } }, h(Icon, { name: 'x', size: 30 })))))
    );
  }

  // ---------------- Promos ----------------
  function Promos() {
    const { go, addItem } = useContext(window.ShopContext);
    return h('div', null,
      h(PageHead, { eyebrow: 'Offers & specials', title: 'This season at the Kitchen', sub: 'A little something extra — refreshed regularly. Available in-house and online.' }),
      h(Wrap, { w: 1100 },
        h('div', { style: { height: 44 } }),
        h('div', { style: { display: 'flex', flexDirection: 'column', gap: 20 } },
          window.DATA.promos.map((p, i) => h('div', { key: p.id, className: 'cust-promo', style: { display: 'grid', gridTemplateColumns: '1fr 1.3fr', gap: 0, background: 'var(--ink-700)', border: '1px solid var(--line)', borderRadius: 18, overflow: 'hidden', minHeight: 220 } },
            h(Ph, { label: 'Promo image', style: { minHeight: 220, order: i % 2 ? 2 : 1 } }),
            h('div', { style: { padding: 36, display: 'flex', flexDirection: 'column', justifyContent: 'center', order: i % 2 ? 1 : 2 } },
              h('div', { style: { display: 'flex', alignItems: 'center', gap: 12, marginBottom: 14 } },
                h('span', { style: { background: p.accent === 'spice' ? 'rgba(156,59,37,.18)' : 'var(--gold-glow)', color: p.accent === 'spice' ? 'var(--spice-500)' : 'var(--gold-400)', border: '1px solid ' + (p.accent === 'spice' ? 'var(--spice-700)' : 'var(--gold-700)'), fontSize: 11, fontWeight: 700, letterSpacing: '.1em', textTransform: 'uppercase', padding: '5px 12px', borderRadius: 999 } }, p.badge),
                h('span', { style: { fontFamily: 'var(--serif)', fontSize: 30, fontWeight: 600, color: 'var(--gold-400)' } }, p.price)),
              h('h3', { style: { fontSize: 30, lineHeight: 1.1 } }, p.title),
              h('p', { style: { color: 'var(--sand)', fontSize: 16, lineHeight: 1.6, marginTop: 12 } }, p.detail),
              h('div', { style: { display: 'flex', gap: 12, marginTop: 22 } }, h('button', { className: 'btn btn-gold btn-sm', onClick: () => go('order') }, 'Order now'), h('button', { className: 'btn btn-ghost btn-sm', onClick: () => go('reserve') }, 'Reserve')))))) ,
        h('div', { style: { ...card, marginTop: 24, display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 24, flexWrap: 'wrap' } },
          h('div', null, h('h3', { style: { fontSize: 26 } }, 'Join the table'), h('p', { style: { color: 'var(--muted)', fontSize: 15, marginTop: 6 } }, 'Get new offers and seasonal menus first. No spam, ever.')),
          h('div', { style: { display: 'flex', gap: 10, flex: 1, minWidth: 260, maxWidth: 420 } }, h('input', { style: inp, placeholder: 'you@email.com', type: 'email' }), h('button', { className: 'btn btn-gold' }, 'Subscribe'))))
    );
  }

  // ---------------- Contact ----------------
  function Contact() {
    const info = [['pin', 'Visit', ['418 Saffron Lane', 'Riverside District, CA 94100']], ['phone', 'Call', ['(415) 555-0192', 'Reservations & takeout']], ['clock', 'Hours', ['Tue–Sun · 11:30 – 22:00', 'Closed Mondays']], ['mail', 'Email', ['hello@indiannepali.kitchen', 'Catering: events@indiannepali.kitchen']]];
    return h('div', null,
      h(PageHead, { eyebrow: 'Visit & contact', title: 'Come find us', sub: 'In the heart of the Riverside District — street parking and a lot around back.' }),
      h(Wrap, { w: 1100 },
        h('div', { style: { height: 44 } }),
        h('div', { className: 'cust-contact-grid', style: { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 28 } },
          h('div', { style: { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 16 } },
            info.map((b, i) => h('div', { key: i, style: card },
              h('div', { style: { width: 44, height: 44, borderRadius: 11, background: 'var(--gold-glow)', border: '1px solid var(--gold-700)', display: 'grid', placeItems: 'center', color: 'var(--gold-400)', marginBottom: 14 } }, h(Icon, { name: b[0], size: 20 })),
              h('h4', { style: { fontSize: 19, marginBottom: 8 } }, b[1]),
              b[2].map((l, j) => h('div', { key: j, style: { color: j === 0 ? 'var(--cream-2)' : 'var(--muted)', fontSize: 14.5, lineHeight: 1.6 } }, l))))),
          h('div', { style: card },
            h('h3', { style: { fontSize: 26, marginBottom: 18 } }, 'Send a message'),
            h('div', { style: { display: 'grid', gap: 14 } },
              h(Field, { label: 'Name' }, h('input', { style: inp, placeholder: 'Your name' })),
              h(Field, { label: 'Email' }, h('input', { style: inp, placeholder: 'you@email.com', type: 'email' })),
              h(Field, { label: 'Message' }, h('textarea', { style: { ...inp, minHeight: 120, resize: 'vertical' }, placeholder: 'How can we help?' }))),
            h('button', { className: 'btn btn-gold', style: { marginTop: 18 } }, 'Send message'))),
        h(Ph, { label: 'Map — 418 Saffron Lane', h: 320, r: 18, style: { marginTop: 24 } }))
    );
  }

  Object.assign(window, { Reserve, Catering, Gallery, Promos, Contact });
})();

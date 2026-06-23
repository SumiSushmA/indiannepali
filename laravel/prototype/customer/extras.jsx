/* About Us + Gift Cards → window.About, window.GiftCard */
(function () {
  const { useContext, useState } = React;
  const h = React.createElement;
  const Icon = window.Icon, Ph = window.Ph, Stars = window.Stars;
  const PageHead = window.PageHead;
  const card = { background: 'var(--ink-700)', border: '1px solid var(--line)', borderRadius: 18, padding: 28 };
  const Wrap = ({ children, w = 1100 }) => h('div', { style: { maxWidth: w, margin: '0 auto', padding: '0 32px 110px' } }, children);

  // ---------------- About ----------------
  function About() {
    const { go } = useContext(window.ShopContext);
    const A = window.DATA.about;
    const toneAccent = { gold: 'var(--gold-400)', spice: 'var(--spice-500)', leaf: 'var(--leaf-500)' };
    return h('div', null,
      h(PageHead, { eyebrow: 'Our story', title: 'Two kitchens, one family', sub: 'A decade of momo, masala and the kind of welcome that brings people back.' }),
      h(Wrap, { w: 1120 },
        h('div', { style: { height: 44 } }),
        // story split
        h('div', { className: 'cust-about-hero', style: { display: 'grid', gridTemplateColumns: '1.1fr 1fr', gap: 48, alignItems: 'center', marginBottom: 90 } },
          h('div', null, A.story.map((p, i) => h('p', { key: i, style: { fontSize: i === 0 ? 22 : 16.5, fontFamily: i === 0 ? 'var(--serif)' : 'var(--sans)', fontStyle: i === 0 ? 'italic' : 'normal', color: i === 0 ? 'var(--cream)' : 'var(--sand)', lineHeight: 1.6, marginBottom: 20 } }, p))),
          h(Ph, { label: 'Founders at the pass', h: 420, r: 18 })),
        // stats band
        h('div', { className: 'cust-stat-band', style: { display: 'grid', gridTemplateColumns: 'repeat(4,1fr)', gap: 24, padding: '40px 0', borderTop: '1px solid var(--line)', borderBottom: '1px solid var(--line)', marginBottom: 90, textAlign: 'center' } },
          A.stats.map((s, i) => h('div', { key: i }, h('div', { style: { fontFamily: 'var(--serif)', fontSize: 48, fontWeight: 600, color: 'var(--gold-400)', lineHeight: 1 } }, s[0]), h('div', { style: { color: 'var(--sand)', fontSize: 14.5, marginTop: 10 } }, s[1])))),
        // values
        h('div', { style: { textAlign: 'center', marginBottom: 44 } }, h('div', { className: 'eyebrow center', style: { justifyContent: 'center', marginBottom: 16 } }, 'What we stand by'), h('h2', { style: { fontSize: 'clamp(30px,4vw,46px)' } }, 'How we cook')),
        h('div', { className: 'cust-val-grid', style: { display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: 24, marginBottom: 90 } },
          A.values.map((v, i) => h('div', { key: i, style: { ...card, textAlign: 'center' } },
            h('div', { style: { width: 56, height: 56, borderRadius: 14, background: 'var(--gold-glow)', border: '1px solid var(--gold-700)', display: 'grid', placeItems: 'center', color: 'var(--gold-400)', margin: '0 auto 18px' } }, h(Icon, { name: v.icon, size: 26 })),
            h('h4', { style: { fontSize: 22, marginBottom: 10 } }, v.title), h('p', { style: { color: 'var(--muted)', fontSize: 15, lineHeight: 1.6 } }, v.text)))),
        // team
        h('div', { style: { textAlign: 'center', marginBottom: 44 } }, h('div', { className: 'eyebrow center', style: { justifyContent: 'center', marginBottom: 16 } }, 'The people'), h('h2', { style: { fontSize: 'clamp(30px,4vw,46px)' } }, 'Meet the kitchen')),
        h('div', { className: 'cust-team-grid', style: { display: 'grid', gridTemplateColumns: 'repeat(4,1fr)', gap: 22, marginBottom: 80 } },
          A.team.map((t, i) => h('div', { key: i },
            h(Ph, { label: t.name, h: 240, r: 16, style: { marginBottom: 16 } }),
            h('div', { className: 'eyebrow', style: { marginBottom: 8, fontSize: 10.5 } }, t.tag),
            h('h4', { style: { fontSize: 21 } }, t.name), h('p', { style: { color: 'var(--muted)', fontSize: 14, marginTop: 4, lineHeight: 1.5 } }, t.role)))),
        // CTA
        h('div', { style: { ...card, textAlign: 'center', padding: '56px 28px', background: 'var(--ink-850)' } },
          h('div', { style: { color: 'var(--gold-600)', fontSize: 30, marginBottom: 10 } }, '◆'),
          h('h2', { style: { fontSize: 'clamp(28px,3.5vw,42px)', maxWidth: 560, margin: '0 auto' } }, 'Come taste a decade of practice'),
          h('div', { style: { display: 'flex', gap: 14, justifyContent: 'center', marginTop: 28, flexWrap: 'wrap' } },
            h('button', { className: 'btn btn-gold', onClick: () => go('reserve') }, h(Icon, { name: 'cal', size: 18 }), 'Reserve a Table'),
            h('button', { className: 'btn btn-ghost', onClick: () => go('order') }, 'See the menu'))))
    );
  }

  // ---------------- Gift Cards ----------------
  function GiftCard() {
    const { go } = useContext(window.ShopContext);
    const { giftDesigns, giftAmounts } = window.DATA;
    const [design, setDesign] = useState('gold');
    const [amount, setAmount] = useState(50);
    const [custom, setCustom] = useState('');
    const [delivery, setDelivery] = useState('email');
    const [done, setDone] = useState(false);
    const value = custom ? Math.max(0, +custom || 0) : amount;
    const accents = { gold: ['linear-gradient(135deg,#3a2a14,#c8852f)', '#f4ecdd'], spice: ['linear-gradient(135deg,#3a1810,#9c3b25)', '#fff'], leaf: ['linear-gradient(135deg,#1c2a18,#4f7d44)', '#fff'] };

    if (done) return h('div', { style: { minHeight: '80vh', display: 'grid', placeItems: 'center', padding: '120px 24px', textAlign: 'center' } },
      h('div', { className: 'fade-up', style: { maxWidth: 480 } },
        h('div', { style: { width: 88, height: 88, borderRadius: 999, margin: '0 auto 26px', background: 'var(--gold-glow)', border: '1px solid var(--gold-600)', display: 'grid', placeItems: 'center', color: 'var(--gold-400)' } }, h(Icon, { name: 'check', size: 42 })),
        h('h1', { style: { fontSize: 44 } }, 'Gift sent!'),
        h('p', { style: { color: 'var(--sand)', fontSize: 17, lineHeight: 1.65, marginTop: 16 } }, 'A $' + value + ' gift card is on its way' + (delivery === 'email' ? ' by email.' : ' — print or hand-deliver it whenever you like.')),
        h('button', { className: 'btn btn-gold', style: { marginTop: 28 }, onClick: () => go('home') }, 'Back to home')));

    const giftCardPreview = h('div', { style: { borderRadius: 18, background: accents[design][0], color: accents[design][1], padding: 26, aspectRatio: '1.6', display: 'flex', flexDirection: 'column', justifyContent: 'space-between', boxShadow: 'var(--shadow-3)', border: '1px solid rgba(255,255,255,.12)', position: 'sticky', top: 100 } },
      h('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' } },
        h('div', null, h('div', { style: { fontFamily: 'var(--serif)', fontSize: 24, fontWeight: 600, lineHeight: 1 } }, 'Indian Nepali'), h('div', { style: { fontSize: 10, letterSpacing: '.4em', textTransform: 'uppercase', marginTop: 5, opacity: .85 } }, 'Kitchen')),
        h('svg', { width: 34, height: 34, viewBox: '0 0 48 48', style: { opacity: .9 } }, h('path', { d: 'M24 11 L33 24 L24 37 L15 24 Z', fill: 'none', stroke: 'currentColor', strokeWidth: 1.6 }), h('circle', { cx: 24, cy: 24, r: 4, fill: 'currentColor' }))),
      h('div', null, h('div', { style: { fontSize: 11, textTransform: 'uppercase', letterSpacing: '.16em', opacity: .8 } }, 'Gift Card'), h('div', { style: { fontFamily: 'var(--serif)', fontSize: 46, fontWeight: 600, lineHeight: 1 } }, '$' + (value || 0))));

    const seg = (val, set, opts) => h('div', { style: { display: 'flex', background: 'var(--ink-800)', border: '1px solid var(--line)', borderRadius: 10, padding: 4, gap: 4 } },
      opts.map(o => h('button', { key: o.v, onClick: () => set(o.v), style: { flex: 1, border: 'none', borderRadius: 7, padding: '11px 10px', cursor: 'pointer', fontWeight: 600, fontSize: 14, fontFamily: 'var(--sans)', background: val === o.v ? 'var(--gold-600)' : 'transparent', color: val === o.v ? '#211405' : 'var(--cream-2)' } }, o.label)));

    return h('div', null,
      h(PageHead, { eyebrow: 'Gift cards', title: 'Give the gift of the feast', sub: 'Send a digital gift card in seconds, or print one to hand over in person. Never expires.' }),
      h(Wrap, { w: 1040 },
        h('div', { style: { height: 44 } }),
        h('div', { className: 'cust-gift-grid', style: { display: 'grid', gridTemplateColumns: '1fr 1.2fr', gap: 40, alignItems: 'start' } },
          giftCardPreview,
          h('div', { style: card },
            // design
            h('div', { style: { fontSize: 13, color: 'var(--sand)', fontWeight: 600, marginBottom: 12 } }, 'Choose a design'),
            h('div', { style: { display: 'flex', gap: 10, marginBottom: 26 } },
              giftDesigns.map(d => h('button', { key: d.id, onClick: () => setDesign(d.id), style: { flex: 1, padding: 12, borderRadius: 12, cursor: 'pointer', textAlign: 'left', background: design === d.id ? 'var(--gold-glow)' : 'var(--ink-800)', border: '1px solid ' + (design === d.id ? 'var(--gold-600)' : 'var(--line)'), color: 'var(--cream)' } },
                h('div', { style: { height: 30, borderRadius: 6, marginBottom: 9, background: accents[d.id][0] } }), h('div', { style: { fontWeight: 600, fontSize: 13.5 } }, d.name), h('div', { style: { fontSize: 11.5, color: 'var(--muted)' } }, d.sub)))),
            // amount
            h('div', { style: { fontSize: 13, color: 'var(--sand)', fontWeight: 600, marginBottom: 12 } }, 'Amount'),
            h('div', { style: { display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: 8, marginBottom: 10 } },
              giftAmounts.map(a => h('button', { key: a, onClick: () => { setAmount(a); setCustom(''); }, style: { padding: '13px 0', borderRadius: 10, cursor: 'pointer', fontWeight: 600, fontSize: 16, fontFamily: 'var(--serif)', background: (!custom && amount === a) ? 'var(--gold-600)' : 'var(--ink-800)', color: (!custom && amount === a) ? '#211405' : 'var(--cream)', border: '1px solid ' + ((!custom && amount === a) ? 'var(--gold-600)' : 'var(--line)') } }, '$' + a))),
            h('input', { value: custom, onChange: e => setCustom(e.target.value.replace(/[^0-9]/g, '')), placeholder: 'Custom amount', inputMode: 'numeric', style: { width: '100%', background: 'var(--ink-800)', border: '1px solid ' + (custom ? 'var(--gold-600)' : 'var(--line)'), borderRadius: 10, padding: '13px 15px', color: 'var(--cream)', fontSize: 15, fontFamily: 'var(--sans)', outline: 'none', marginBottom: 26 } }),
            // delivery
            h('div', { style: { fontSize: 13, color: 'var(--sand)', fontWeight: 600, marginBottom: 12 } }, 'How to deliver'),
            h('div', { style: { marginBottom: 18 } }, seg(delivery, setDelivery, [{ v: 'email', label: 'Email' }, { v: 'print', label: 'Print at home' }, { v: 'mail', label: 'Physical card' }])),
            // recipient
            h('div', { style: { display: 'grid', gap: 14, marginBottom: 22 } },
              h('input', { placeholder: delivery === 'email' ? 'Recipient email' : 'Recipient name', style: giftInp }),
              h('input', { placeholder: 'Your name', style: giftInp }),
              h('textarea', { placeholder: 'Add a message (optional)', style: { ...giftInp, minHeight: 70, resize: 'vertical' } })),
            h('button', { className: 'btn btn-gold', style: { width: '100%', justifyContent: 'center' }, disabled: !value, onClick: () => { setDone(true); window.scrollTo({ top: 0 }); } }, h(Icon, { name: 'tag', size: 18 }), 'Buy gift card · $' + (value || 0)))),
        // redeem strip
        h('div', { style: { ...card, marginTop: 24, display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 20, flexWrap: 'wrap' } },
          h('div', null, h('h3', { style: { fontSize: 22 } }, 'Have a gift card?'), h('p', { style: { color: 'var(--muted)', fontSize: 14.5, marginTop: 6 } }, 'Enter the code at checkout, or check a balance below.')),
          h('div', { style: { display: 'flex', gap: 10, flex: 1, minWidth: 260, maxWidth: 420 } }, h('input', { placeholder: 'Gift card code', style: { ...giftInp, flex: 1 } }), h('button', { className: 'btn btn-ghost' }, 'Check balance'))))
    );
  }
  const giftInp = { width: '100%', background: 'var(--ink-800)', border: '1px solid var(--line)', borderRadius: 10, padding: '13px 15px', color: 'var(--cream)', fontSize: 15, fontFamily: 'var(--sans)', outline: 'none' };

  Object.assign(window, { About, GiftCard });
})();

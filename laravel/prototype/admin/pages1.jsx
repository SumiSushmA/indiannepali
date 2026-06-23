/* Admin: Orders, Reservations (+calendar), Menu → window */
(function () {
  const { useContext, useState } = React;
  const h = React.createElement;
  const Icon = window.Icon;
  const { Card, Badge, Table, Td, IconBtn, PageTitle, Segmented } = window.AW;

  const statusTone = { New: 'gold', Preparing: 'blue', Ready: 'green', 'Out for delivery': 'purple', Completed: 'neutral', Confirmed: 'green', Seated: 'gold', Pending: 'blue', Cancelled: 'red', Quoted: 'blue', 'In conversation': 'purple', Booked: 'green', Unread: 'red', Open: 'gold', Resolved: 'neutral' };

  function Toolbar({ children }) {
    return h('div', { style: { display: 'flex', gap: 10, alignItems: 'center', flexWrap: 'wrap', marginBottom: 18 } }, children);
  }
  function Search({ value, onChange, ph }) {
    return h('div', { style: { display: 'flex', alignItems: 'center', gap: 9, background: 'var(--ink-700)', border: '1px solid var(--line)', borderRadius: 10, padding: '0 13px', minWidth: 220 } },
      h(Icon, { name: 'search', size: 16, color: 'var(--muted)' }),
      h('input', { value, onChange: e => onChange(e.target.value), placeholder: ph, style: { flex: 1, background: 'none', border: 'none', outline: 'none', color: 'var(--cream)', fontSize: 14, padding: '10px 0', fontFamily: 'var(--sans)' } }));
  }

  // ---------------- Orders ----------------
  function Orders() {
    const A = window.ADMIN;
    const [tab, setTab] = useState('All');
    const [q, setQ] = useState('');
    const [sel, setSel] = useState(null);
    const tabs = ['All', 'New', 'Preparing', 'Ready', 'Out for delivery', 'Completed'];
    const counts = tabs.reduce((m, t) => (m[t] = t === 'All' ? A.orders.length : A.orders.filter(o => o.status === t).length, m), {});
    const rows = A.orders.filter(o => (tab === 'All' || o.status === tab) && (!q || (o.id + o.customer).toLowerCase().includes(q.toLowerCase())));

    return h('div', null,
      h(PageTitle, { title: 'Orders', sub: A.orders.filter(o => o.status === 'New').length + ' new · ' + A.orders.filter(o => ['Preparing', 'Ready', 'Out for delivery'].includes(o.status)).length + ' in progress', actions: h('button', { className: 'btn btn-gold btn-sm' }, h(Icon, { name: 'plus', size: 16 }), 'New order') }),
      h('div', { style: { display: 'flex', gap: 6, marginBottom: 18, overflowX: 'auto', borderBottom: '1px solid var(--line)', paddingBottom: 0 } },
        tabs.map(t => h('button', { key: t, onClick: () => setTab(t), style: { background: 'none', border: 'none', borderBottom: '2px solid ' + (tab === t ? 'var(--gold-500)' : 'transparent'), color: tab === t ? 'var(--cream)' : 'var(--muted)', cursor: 'pointer', padding: '10px 14px', fontSize: 14, fontWeight: 600, fontFamily: 'var(--sans)', whiteSpace: 'nowrap', display: 'flex', gap: 8, alignItems: 'center', marginBottom: -1 } }, t, h('span', { style: { background: 'var(--ink-700)', borderRadius: 99, fontSize: 11, padding: '1px 7px', color: 'var(--sand)' } }, counts[t])))),
      h(Toolbar, null, h(Search, { value: q, onChange: setQ, ph: 'Search by order # or guest…' }), h('button', { className: 'btn btn-ghost btn-sm' }, h(Icon, { name: 'filter', size: 15 }), 'Filters')),
      h(Card, { pad: '8px 8px' },
        h(Table, { cols: [{ label: 'Order' }, { label: 'Guest' }, { label: 'Type' }, { label: 'Channel' }, { label: 'Items' }, { label: 'Total', right: true }, { label: 'Status' }, { label: '', right: true }] },
          rows.map(o => h('tr', { key: o.id, onClick: () => setSel(o), style: { cursor: 'pointer' }, onMouseEnter: e => e.currentTarget.style.background = 'var(--ink-750)', onMouseLeave: e => e.currentTarget.style.background = 'transparent' },
            h(Td, null, h('span', { style: { fontWeight: 700, color: 'var(--cream)' } }, o.id), h('div', { style: { fontSize: 12, color: 'var(--muted)' } }, o.time)),
            h(Td, null, o.customer),
            h(Td, null, h('span', { style: { display: 'inline-flex', gap: 6, alignItems: 'center' } }, h(Icon, { name: o.type === 'Delivery' ? 'truck' : 'bag', size: 15, color: 'var(--muted)' }), o.type)),
            h(Td, null, h('span', { style: { fontSize: 13, color: 'var(--sand)' } }, o.channel)),
            h(Td, null, o.items.reduce((n, i) => n + i.qty, 0)),
            h(Td, { right: true }, h('span', { style: { fontWeight: 600, fontFamily: 'var(--serif)', fontSize: 16, color: 'var(--cream)' } }, '$' + o.total)),
            h(Td, null, h(Badge, { tone: statusTone[o.status], dot: true }, o.status)),
            h(Td, { right: true }, h(IconBtn, { icon: 'arrow' })))))),
      sel && h(OrderDrawer, { o: sel, onClose: () => setSel(null) }));
  }

  function Drawer({ title, sub, onClose, children, footer, w = 460 }) {
    return h('div', { style: { position: 'fixed', inset: 0, zIndex: 300 } },
      h('div', { onClick: onClose, style: { position: 'absolute', inset: 0, background: 'rgba(0,0,0,.55)', backdropFilter: 'blur(3px)' } }),
      h('div', { className: 'fade-up', style: { position: 'absolute', top: 0, right: 0, bottom: 0, width: 'min(' + w + 'px, 94vw)', background: 'var(--ink-750)', borderLeft: '1px solid var(--line)', display: 'flex', flexDirection: 'column', boxShadow: '-30px 0 60px rgba(0,0,0,.5)' } },
        h('div', { style: { padding: '22px 26px', borderBottom: '1px solid var(--line)', display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' } },
          h('div', null, h('h3', { style: { fontSize: 23, fontWeight: 600 } }, title), sub && h('div', { style: { fontSize: 13.5, color: 'var(--muted)', marginTop: 4 } }, sub)),
          h('button', { onClick: onClose, style: { background: 'none', border: 'none', color: 'var(--sand)', cursor: 'pointer' } }, h(Icon, { name: 'x', size: 22 }))),
        h('div', { style: { flex: 1, overflowY: 'auto', padding: 26 } }, children),
        footer && h('div', { style: { padding: 20, borderTop: '1px solid var(--line)', background: 'var(--ink-800)', display: 'flex', gap: 10 } }, footer)));
  }

  function OrderDrawer({ o, onClose }) {
    const [status, setStatus] = useState(o.status);
    const sub = o.total, tax = sub * .0875;
    const flow = ['New', 'Preparing', 'Ready', o.type === 'Delivery' ? 'Out for delivery' : 'Completed', 'Completed'];
    return h(Drawer, { title: o.id, sub: o.customer + ' · ' + o.time, onClose,
      footer: [h('button', { key: 1, className: 'btn btn-ghost btn-sm', style: { flex: 1, justifyContent: 'center' } }, 'Print ticket'), h('button', { key: 2, className: 'btn btn-gold btn-sm', style: { flex: 1, justifyContent: 'center' } }, 'Advance to ' + (flow[flow.indexOf(status) + 1] || 'Done'))] },
      h('div', { style: { display: 'flex', gap: 10, marginBottom: 22 } }, h(Badge, { tone: o.type === 'Delivery' ? 'purple' : 'gold', dot: true }, o.type), h(Badge, { tone: 'neutral' }, o.channel)),
      // status stepper
      h('div', { style: { marginBottom: 24 } },
        h('div', { style: { fontSize: 12, fontWeight: 700, letterSpacing: '.08em', textTransform: 'uppercase', color: 'var(--muted)', marginBottom: 12 } }, 'Status'),
        h('div', { style: { display: 'flex', flexDirection: 'column', gap: 0 } },
          [...new Set(flow)].map((s, i, arr) => { const done = arr.indexOf(status) >= i; const cur = status === s; return h('div', { key: s, onClick: () => setStatus(s), style: { display: 'flex', alignItems: 'center', gap: 12, padding: '7px 0', cursor: 'pointer' } },
            h('div', { style: { width: 22, height: 22, borderRadius: 999, border: '2px solid ' + (done ? 'var(--gold-500)' : 'var(--line)'), background: done ? 'var(--gold-500)' : 'transparent', display: 'grid', placeItems: 'center', flexShrink: 0 } }, done && h(Icon, { name: 'check', size: 12, color: '#211405', stroke: 3 })),
            h('span', { style: { fontSize: 14.5, fontWeight: cur ? 700 : 500, color: done ? 'var(--cream)' : 'var(--muted)' } }, s)); }))),
      // items
      h('div', { style: { fontSize: 12, fontWeight: 700, letterSpacing: '.08em', textTransform: 'uppercase', color: 'var(--muted)', marginBottom: 12 } }, 'Items'),
      o.items.map((it, i) => h('div', { key: i, style: { display: 'flex', justifyContent: 'space-between', padding: '10px 0', borderBottom: '1px solid var(--line-soft)', fontSize: 14.5 } }, h('span', null, h('span', { style: { color: 'var(--gold-500)', fontWeight: 700, marginRight: 8 } }, it.qty + '×'), it.name), h('span', { style: { color: 'var(--sand)' } }, '$' + (12 * it.qty)))),
      h('div', { style: { marginTop: 16, paddingTop: 14, borderTop: '1px solid var(--line)' } },
        ['Subtotal,' + sub.toFixed(2), 'Tax,' + tax.toFixed(2)].map((r, i) => h('div', { key: i, style: { display: 'flex', justifyContent: 'space-between', fontSize: 14, color: 'var(--sand)', marginBottom: 6 } }, h('span', null, r.split(',')[0]), h('span', null, '$' + r.split(',')[1]))),
        h('div', { style: { display: 'flex', justifyContent: 'space-between', fontFamily: 'var(--serif)', fontSize: 20, fontWeight: 700, marginTop: 8 } }, h('span', null, 'Total'), h('span', { style: { color: 'var(--gold-400)' } }, '$' + (sub + tax).toFixed(2)))));
  }

  // ---------------- Reservations ----------------
  function Reservations() {
    const A = window.ADMIN;
    const [mode, setMode] = useState('Calendar');
    const [q, setQ] = useState('');
    const [sel, setSel] = useState(null);
    return h('div', null,
      h(PageTitle, { title: 'Reservations', sub: A.reservations.filter(r => r.status === 'Pending').length + ' pending · ' + A.reservations.filter(r => r.status === 'Confirmed').length + ' confirmed', actions: [h(Segmented, { key: 1, options: ['Calendar', 'List'], value: mode, onChange: setMode }), h('button', { key: 2, className: 'btn btn-gold btn-sm' }, h(Icon, { name: 'plus', size: 16 }), 'Add booking')] }),
      mode === 'Calendar' ? h(CalendarView, { onPick: setSel }) : h(ResList, { q, setQ, onPick: setSel }),
      sel && h(ResDrawer, { r: sel, onClose: () => setSel(null) }));
  }

  function CalendarView({ onPick }) {
    const A = window.ADMIN;
    const dow = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const firstDay = new Date(2026, 5, 1).getDay(); // June 2026 starts Monday=1
    const days = 30;
    const cells = [];
    for (let i = 0; i < firstDay; i++) cells.push(null);
    for (let d = 1; d <= days; d++) cells.push(d);
    const today = 2;
    const dayRes = (d) => A.reservations.filter(r => +r.date.slice(-2) === d);
    return h('div', { className: 'adm-cal-wrap', style: { display: 'grid', gridTemplateColumns: '1fr 320px', gap: 18, alignItems: 'start' } },
      h(Card, { pad: 20 },
        h('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 18 } },
          h('h3', { style: { fontSize: 21, fontWeight: 600 } }, 'June 2026'),
          h('div', { style: { display: 'flex', gap: 8 } }, h(window.AW.IconBtn, { icon: 'arrowL' }), h(window.AW.IconBtn, { icon: 'arrow' }))),
        h('div', { style: { display: 'grid', gridTemplateColumns: 'repeat(7,1fr)', gap: 6 } },
          dow.map(d => h('div', { key: d, style: { textAlign: 'center', fontSize: 11, fontWeight: 700, letterSpacing: '.06em', textTransform: 'uppercase', color: 'var(--faint)', padding: '4px 0' } }, d)),
          cells.map((d, i) => d === null ? h('div', { key: 'e' + i }) : h(CalCell, { key: d, d, count: A.calCounts[d] || 0, isToday: d === today, onClick: () => onPick(dayRes(d)[0] || { ...A.reservations[0], date: '2026-06-' + String(d).padStart(2, '0') }) })))),
      // side: today's bookings
      h(Card, { pad: 0 },
        h('div', { style: { padding: '18px 20px', borderBottom: '1px solid var(--line)' } }, h('h3', { style: { fontSize: 18, fontWeight: 600 } }, 'Tonight\u2019s service'), h('div', { style: { fontSize: 13, color: 'var(--muted)', marginTop: 3 } }, 'Tue, Jun 2 · 8 tables seated')),
        h('div', { style: { padding: 12 } },
          A.reservations.slice(0, 7).map(r => h('div', { key: r.id, onClick: () => onPick(r), style: { display: 'flex', alignItems: 'center', gap: 12, padding: 11, borderRadius: 10, cursor: 'pointer' }, onMouseEnter: e => e.currentTarget.style.background = 'var(--ink-800)', onMouseLeave: e => e.currentTarget.style.background = 'transparent' },
            h('div', { style: { fontFamily: 'var(--serif)', fontSize: 15, fontWeight: 600, color: 'var(--gold-400)', width: 48, flexShrink: 0 } }, r.time),
            h('div', { style: { flex: 1, minWidth: 0 } }, h('div', { style: { fontWeight: 600, fontSize: 14 } }, r.name), h('div', { style: { fontSize: 12.5, color: 'var(--muted)' } }, r.party + ' guests · ' + r.table)),
            h(Badge, { tone: statusTone[r.status] }, r.status))))));
  }

  function CalCell({ d, count, isToday, onClick }) {
    const heat = count === 0 ? 0 : count < 6 ? 1 : count < 12 ? 2 : 3;
    const bg = ['transparent', 'rgba(200,133,47,.1)', 'rgba(200,133,47,.2)', 'rgba(200,133,47,.34)'][heat];
    return h('button', { onClick, style: { aspectRatio: '1', border: '1px solid ' + (isToday ? 'var(--gold-500)' : 'var(--line-soft)'), background: bg, borderRadius: 10, cursor: 'pointer', padding: 8, display: 'flex', flexDirection: 'column', alignItems: 'flex-start', justifyContent: 'space-between', transition: 'all .15s', color: 'var(--cream)' }, onMouseEnter: e => e.currentTarget.style.borderColor = 'var(--gold-600)', onMouseLeave: e => e.currentTarget.style.borderColor = isToday ? 'var(--gold-500)' : 'var(--line-soft)' },
      h('span', { style: { fontSize: 13.5, fontWeight: isToday ? 700 : 500, color: isToday ? 'var(--gold-400)' : 'var(--cream-2)' } }, d),
      count > 0 && h('span', { style: { fontSize: 11, color: 'var(--sand)', fontWeight: 600 } }, count + ' bk'));
  }

  function ResList({ q, setQ, onPick }) {
    const A = window.ADMIN;
    const rows = A.reservations.filter(r => !q || (r.name + r.id).toLowerCase().includes(q.toLowerCase()));
    return h('div', null,
      h(Toolbar, null, h(Search, { value: q, onChange: setQ, ph: 'Search guest or booking #…' }), h('button', { className: 'btn btn-ghost btn-sm' }, h(Icon, { name: 'filter', size: 15 }), 'Filters')),
      h(Card, { pad: '8px 8px' },
        h(Table, { cols: [{ label: 'Booking' }, { label: 'Guest' }, { label: 'Date' }, { label: 'Time' }, { label: 'Party' }, { label: 'Table' }, { label: 'Occasion' }, { label: 'Status' }] },
          rows.map(r => h('tr', { key: r.id, onClick: () => onPick(r), style: { cursor: 'pointer' }, onMouseEnter: e => e.currentTarget.style.background = 'var(--ink-750)', onMouseLeave: e => e.currentTarget.style.background = 'transparent' },
            h(Td, null, h('span', { style: { fontWeight: 700, color: 'var(--cream)' } }, r.id)),
            h(Td, null, r.name), h(Td, null, r.date), h(Td, null, r.time),
            h(Td, null, r.party), h(Td, null, r.table), h(Td, null, h('span', { style: { color: r.occasion === '—' ? 'var(--faint)' : 'var(--cream-2)' } }, r.occasion)),
            h(Td, null, h(Badge, { tone: statusTone[r.status], dot: true }, r.status)))))));
  }

  function ResDrawer({ r, onClose }) {
    const row = (l, v) => h('div', { style: { display: 'flex', justifyContent: 'space-between', padding: '13px 0', borderBottom: '1px solid var(--line-soft)' } }, h('span', { style: { color: 'var(--muted)', fontSize: 14 } }, l), h('span', { style: { fontWeight: 600, fontSize: 14.5 } }, v));
    return h(Drawer, { title: r.name, sub: r.id + ' · ' + r.phone, onClose, footer: [h('button', { key: 1, className: 'btn btn-ghost btn-sm', style: { flex: 1, justifyContent: 'center' } }, 'Message guest'), h('button', { key: 2, className: 'btn btn-gold btn-sm', style: { flex: 1, justifyContent: 'center' } }, 'Confirm & seat')] },
      h('div', { style: { marginBottom: 18 } }, h(Badge, { tone: statusTone[r.status], dot: true }, r.status)),
      row('Date', r.date), row('Time', r.time), row('Party size', r.party + ' guests'), row('Table', r.table), row('Occasion', r.occasion), row('Phone', r.phone),
      h('div', { style: { marginTop: 18, fontSize: 12, fontWeight: 700, letterSpacing: '.08em', textTransform: 'uppercase', color: 'var(--muted)', marginBottom: 8 } }, 'Notes'),
      h('div', { style: { background: 'var(--ink-800)', border: '1px solid var(--line)', borderRadius: 10, padding: 14, fontSize: 14, color: 'var(--sand)', lineHeight: 1.6 } }, r.occasion !== '—' ? r.occasion + ' celebration — please prepare a complimentary dessert with a candle.' : 'No special requests. Window seating preferred if available.'));
  }

  // ---------------- Menu management ----------------
  function MenuMgmt() {
    const A = window.ADMIN;
    const { menu } = window.DATA;
    const [cat, setCat] = useState('all');
    const [q, setQ] = useState('');
    const [avail, setAvail] = useState(() => Object.fromEntries(menu.items.map(i => [i.id, !(i.id === 'c2')])));
    const rows = menu.items.filter(i => (cat === 'all' || i.cat === cat) && (!q || i.name.toLowerCase().includes(q.toLowerCase())));
    return h('div', null,
      h(PageTitle, { title: 'Menu', sub: menu.items.length + ' items · ' + Object.values(avail).filter(Boolean).length + ' available · synced with Toast POS', actions: h('button', { className: 'btn btn-gold btn-sm' }, h(Icon, { name: 'plus', size: 16 }), 'Add item') }),
      h(Toolbar, null,
        h(Search, { value: q, onChange: setQ, ph: 'Search dishes…' }),
        h('div', { style: { display: 'flex', gap: 6, overflowX: 'auto' } },
          h('button', { onClick: () => setCat('all'), style: catChip(cat === 'all') }, 'All'),
          menu.categories.map(c => h('button', { key: c.id, onClick: () => setCat(c.id), style: catChip(cat === c.id) }, c.name)))),
      h(Card, { pad: '8px 8px' },
        h(Table, { cols: [{ label: 'Dish' }, { label: 'Category' }, { label: 'Diet' }, { label: 'Price', right: true }, { label: 'POS ID' }, { label: 'Available' }, { label: '', right: true }] },
          rows.map(it => h('tr', { key: it.id, onMouseEnter: e => e.currentTarget.style.background = 'var(--ink-750)', onMouseLeave: e => e.currentTarget.style.background = 'transparent' },
            h(Td, null, h('div', { style: { display: 'flex', alignItems: 'center', gap: 12 } }, h(window.Ph, { label: it.img, style: { width: 42, height: 42, flexShrink: 0 }, r: 8 }), h('div', null, h('div', { style: { fontWeight: 600, color: 'var(--cream)' } }, it.name), it.popular && h('span', { style: { fontSize: 11, color: 'var(--gold-500)', fontWeight: 600 } }, '★ Popular')))),
            h(Td, null, h('span', { style: { fontSize: 13, color: 'var(--sand)' } }, menu.categories.find(c => c.id === it.cat).name)),
            h(Td, null, it.veg ? h(Badge, { tone: 'green' }, 'Veg') : h(Badge, { tone: 'neutral' }, 'Non-veg')),
            h(Td, { right: true }, h('span', { style: { fontWeight: 600, fontFamily: 'var(--serif)', fontSize: 16 } }, '$' + it.price)),
            h(Td, null, h('span', { style: { fontFamily: 'ui-monospace,monospace', fontSize: 12.5, color: 'var(--muted)' } }, 'TST-' + it.id.toUpperCase() + '0' + it.price)),
            h(Td, null, h(Toggle, { on: avail[it.id], onClick: () => setAvail(a => ({ ...a, [it.id]: !a[it.id] })) })),
            h(Td, { right: true }, h('div', { style: { display: 'flex', gap: 6, justifyContent: 'flex-end' } }, h(IconBtn, { icon: 'edit' }), h(IconBtn, { icon: 'dots' }))))))));
  }
  const catChip = (active) => ({ flexShrink: 0, background: active ? 'var(--gold-600)' : 'var(--ink-700)', color: active ? '#211405' : 'var(--cream-2)', border: '1px solid ' + (active ? 'var(--gold-600)' : 'var(--line)'), borderRadius: 999, padding: '9px 15px', cursor: 'pointer', fontSize: 13, fontWeight: 600, fontFamily: 'var(--sans)', whiteSpace: 'nowrap' });

  function Toggle({ on, onClick }) {
    return h('button', { onClick, role: 'switch', 'aria-checked': on, style: { width: 42, height: 24, borderRadius: 999, border: 'none', cursor: 'pointer', background: on ? 'var(--leaf-600)' : 'var(--ink-600)', position: 'relative', transition: 'background .2s', padding: 0 } },
      h('span', { style: { position: 'absolute', top: 3, left: on ? 21 : 3, width: 18, height: 18, borderRadius: 999, background: '#fff', transition: 'left .2s', boxShadow: '0 1px 3px rgba(0,0,0,.4)' } }));
  }

  Object.assign(window, { AdminOrders: Orders, AdminReservations: Reservations, AdminMenu: MenuMgmt, AdminDrawer: Drawer, AdminToggle: Toggle, statusTone });
})();

/* Admin: Catering, Contact, Content, Gallery, Users, Toast, Settings → window */
(function () {
  const { useContext, useState } = React;
  const h = React.createElement;
  const Icon = window.Icon, Ph = window.Ph;
  const { Card, Badge, Table, Td, IconBtn, PageTitle, Segmented } = window.AW;
  const statusTone = window.statusTone;
  const Drawer = window.AdminDrawer, Toggle = window.AdminToggle;

  function Search({ value, onChange, ph }) {
    return h('div', { style: { display: 'flex', alignItems: 'center', gap: 9, background: 'var(--ink-700)', border: '1px solid var(--line)', borderRadius: 10, padding: '0 13px', minWidth: 220, marginBottom: 18 } },
      h(Icon, { name: 'search', size: 16, color: 'var(--muted)' }),
      h('input', { value, onChange: e => onChange(e.target.value), placeholder: ph, style: { flex: 1, background: 'none', border: 'none', outline: 'none', color: 'var(--cream)', fontSize: 14, padding: '10px 0', fontFamily: 'var(--sans)' } }));
  }

  // ---------------- Catering ----------------
  function Catering() {
    const A = window.ADMIN;
    const [sel, setSel] = useState(null);
    const pipeline = ['New', 'Quoted', 'In conversation', 'Booked'];
    const byStage = s => A.catering.filter(c => c.status === s);
    const pipelineValue = A.catering.filter(c => c.status !== 'New').reduce((s, c) => s + c.value, 0);
    return h('div', null,
      h(PageTitle, { title: 'Catering inquiries', sub: byStage('New').length + ' new leads · $' + pipelineValue.toLocaleString() + ' in pipeline', actions: h(Segmented, { options: ['Board', 'List'], value: 'Board', onChange: () => {} }) }),
      h('div', { className: 'adm-kanban', style: { display: 'grid', gridTemplateColumns: 'repeat(4,1fr)', gap: 16, alignItems: 'start' } },
        pipeline.map(stage => h('div', { key: stage, style: { background: 'var(--ink-850)', border: '1px solid var(--line)', borderRadius: 14, padding: 12 } },
          h('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '6px 8px 12px' } },
            h('span', { style: { fontWeight: 600, fontSize: 14.5, display: 'flex', gap: 8, alignItems: 'center' } }, h('span', { style: { width: 8, height: 8, borderRadius: 99, background: window.AW.toneMap[statusTone[stage]][1] } }), stage),
            h('span', { style: { fontSize: 12, color: 'var(--muted)', background: 'var(--ink-700)', borderRadius: 99, padding: '2px 8px' } }, byStage(stage).length)),
          h('div', { style: { display: 'flex', flexDirection: 'column', gap: 10 } },
            byStage(stage).map(c => h('div', { key: c.id, onClick: () => setSel(c), style: { background: 'var(--ink-700)', border: '1px solid var(--line)', borderRadius: 11, padding: 14, cursor: 'pointer', transition: 'border-color .2s, transform .2s' }, onMouseEnter: e => { e.currentTarget.style.borderColor = 'var(--ink-600)'; e.currentTarget.style.transform = 'translateY(-2px)'; }, onMouseLeave: e => { e.currentTarget.style.borderColor = 'var(--line)'; e.currentTarget.style.transform = 'none'; } },
              h('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 8 } }, h('span', { style: { fontWeight: 600, fontSize: 14 } }, c.name), h(Badge, { tone: 'gold' }, c.event)),
              h('div', { style: { display: 'flex', gap: 14, fontSize: 12.5, color: 'var(--muted)', marginBottom: 10 } }, h('span', { style: { display: 'flex', gap: 5, alignItems: 'center' } }, h(Icon, { name: 'users', size: 13 }), c.guests), h('span', { style: { display: 'flex', gap: 5, alignItems: 'center' } }, h(Icon, { name: 'cal', size: 13 }), c.date.slice(5))),
              h('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', paddingTop: 10, borderTop: '1px solid var(--line-soft)' } }, h('span', { style: { fontFamily: 'var(--serif)', fontSize: 17, fontWeight: 600, color: 'var(--gold-400)' } }, '$' + c.value.toLocaleString()), h('span', { style: { fontSize: 11.5, color: 'var(--faint)' } }, c.days === 0 ? 'today' : c.days + 'd ago')))))))),
      sel && h(CateringDrawer, { c: sel, onClose: () => setSel(null) }));
  }
  function CateringDrawer({ c, onClose }) {
    const row = (l, v) => h('div', { style: { display: 'flex', justifyContent: 'space-between', padding: '13px 0', borderBottom: '1px solid var(--line-soft)' } }, h('span', { style: { color: 'var(--muted)', fontSize: 14 } }, l), h('span', { style: { fontWeight: 600, fontSize: 14.5 } }, v));
    return h(Drawer, { title: c.name, sub: c.id + ' · ' + c.event, onClose, footer: [h('button', { key: 1, className: 'btn btn-ghost btn-sm', style: { flex: 1, justifyContent: 'center' } }, 'Reply'), h('button', { key: 2, className: 'btn btn-gold btn-sm', style: { flex: 1, justifyContent: 'center' } }, 'Build quote')] },
      h('div', { style: { marginBottom: 18 } }, h(Badge, { tone: statusTone[c.status], dot: true }, c.status)),
      row('Event type', c.event), row('Guest count', c.guests + ' guests'), row('Event date', c.date), row('Est. value', '$' + c.value.toLocaleString()),
      h('div', { style: { marginTop: 18, fontSize: 12, fontWeight: 700, letterSpacing: '.08em', textTransform: 'uppercase', color: 'var(--muted)', marginBottom: 8 } }, 'Message'),
      h('div', { style: { background: 'var(--ink-800)', border: '1px solid var(--line)', borderRadius: 10, padding: 14, fontSize: 14, color: 'var(--sand)', lineHeight: 1.6 } }, 'Hi! We\u2019re planning a ' + c.event.toLowerCase() + ' for around ' + c.guests + ' guests on ' + c.date + '. We\u2019d love a mix of momo, biryani and a couple of vegetarian curries. Can you send a proposal and tasting options?'));
  }

  // ---------------- Contact ----------------
  function Contact() {
    const A = window.ADMIN;
    const [sel, setSel] = useState(A.contact[0]);
    const [tab, setTab] = useState('All');
    const tabs = ['All', 'Unread', 'Open', 'Resolved'];
    const rows = A.contact.filter(m => tab === 'All' || m.status === tab);
    return h('div', null,
      h(PageTitle, { title: 'Contact inbox', sub: A.contact.filter(m => m.status === 'Unread').length + ' unread · ' + A.contact.filter(m => m.status === 'Open').length + ' open' }),
      h('div', { className: 'adm-inbox', style: { display: 'grid', gridTemplateColumns: '380px 1fr', gap: 18, alignItems: 'start', height: 'calc(100vh - 220px)' } },
        h(Card, { pad: 0, style: { display: 'flex', flexDirection: 'column', height: '100%', overflow: 'hidden' } },
          h('div', { style: { display: 'flex', gap: 4, padding: 10, borderBottom: '1px solid var(--line)' } }, tabs.map(t => h('button', { key: t, onClick: () => setTab(t), style: { flex: 1, background: tab === t ? 'var(--ink-600)' : 'transparent', border: 'none', borderRadius: 8, padding: '8px 0', cursor: 'pointer', color: tab === t ? 'var(--cream)' : 'var(--muted)', fontSize: 13, fontWeight: 600, fontFamily: 'var(--sans)' } }, t))),
          h('div', { style: { flex: 1, overflowY: 'auto' } },
            rows.map(m => h('button', { key: m.id, onClick: () => setSel(m), style: { display: 'block', width: '100%', textAlign: 'left', border: 'none', borderBottom: '1px solid var(--line-soft)', borderLeft: '3px solid ' + (sel.id === m.id ? 'var(--gold-500)' : 'transparent'), background: sel.id === m.id ? 'var(--ink-750)' : 'transparent', padding: '14px 16px', cursor: 'pointer' } },
              h('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 4 } }, h('span', { style: { fontWeight: m.status === 'Unread' ? 700 : 600, fontSize: 14, color: 'var(--cream)' } }, m.name), h('span', { style: { fontSize: 11.5, color: 'var(--faint)' } }, m.days === 0 ? 'today' : m.days + 'd')),
              h('div', { style: { display: 'flex', gap: 8, alignItems: 'center', marginBottom: 4 } }, m.status === 'Unread' && h('span', { style: { width: 7, height: 7, borderRadius: 99, background: 'var(--spice-500)' } }), h('span', { style: { fontSize: 13, fontWeight: 600, color: 'var(--sand)' } }, m.subject)),
              h('div', { style: { fontSize: 12.5, color: 'var(--muted)', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' } }, m.preview)))),
        ),
        h(Card, { style: { height: '100%', display: 'flex', flexDirection: 'column' }, pad: 0 },
          h('div', { style: { padding: '20px 26px', borderBottom: '1px solid var(--line)', display: 'flex', justifyContent: 'space-between', alignItems: 'center' } },
            h('div', null, h('h3', { style: { fontSize: 21, fontWeight: 600 } }, sel.subject), h('div', { style: { fontSize: 13.5, color: 'var(--muted)', marginTop: 3 } }, sel.name + ' · ' + sel.email)),
            h(Badge, { tone: statusTone[sel.status], dot: true }, sel.status)),
          h('div', { style: { flex: 1, overflowY: 'auto', padding: 26 } },
            h('div', { style: { background: 'var(--ink-800)', border: '1px solid var(--line)', borderRadius: 12, padding: 18, fontSize: 15, lineHeight: 1.7, color: 'var(--cream-2)' } }, sel.preview + ' I really hope you can help. Thanks so much for a wonderful experience — we\u2019ll definitely be back. Warm regards, ' + sel.name.split(' ')[0] + '.')),
          h('div', { style: { padding: 18, borderTop: '1px solid var(--line)', background: 'var(--ink-800)' } },
            h('textarea', { placeholder: 'Write a reply…', style: { width: '100%', background: 'var(--ink-750)', border: '1px solid var(--line)', borderRadius: 10, padding: 13, color: 'var(--cream)', fontSize: 14, fontFamily: 'var(--sans)', minHeight: 70, resize: 'none', outline: 'none' } }),
            h('div', { style: { display: 'flex', gap: 10, marginTop: 12 } }, h('button', { className: 'btn btn-ghost btn-sm' }, 'Mark resolved'), h('button', { className: 'btn btn-gold btn-sm', style: { marginLeft: 'auto' } }, h(Icon, { name: 'mail', size: 15 }), 'Send reply'))))));
  }

  // ---------------- Content / CMS ----------------
  function ContentCMS() {
    const A = window.ADMIN;
    const tone = { Text: 'neutral', Promotion: 'gold', Media: 'purple' };
    return h('div', null,
      h(PageTitle, { title: 'Website content', sub: 'Edit the live customer site copy, promotions and media.', actions: h('button', { className: 'btn btn-gold btn-sm' }, h(Icon, { name: 'plus', size: 16 }), 'New block') }),
      h(Card, { pad: '8px 8px' },
        h(Table, { cols: [{ label: 'Section' }, { label: 'Current value' }, { label: 'Type' }, { label: 'Updated' }, { label: '', right: true }] },
          A.content.map((c, i) => h('tr', { key: i, onMouseEnter: e => e.currentTarget.style.background = 'var(--ink-750)', onMouseLeave: e => e.currentTarget.style.background = 'transparent' },
            h(Td, null, h('span', { style: { fontWeight: 600, color: 'var(--cream)' } }, c.section)),
            h(Td, null, h('span', { style: { color: 'var(--sand)', display: 'block', maxWidth: 360, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' } }, c.value)),
            h(Td, null, h(Badge, { tone: tone[c.type] }, c.type)),
            h(Td, null, h('span', { style: { fontSize: 13, color: 'var(--muted)' } }, c.updated)),
            h(Td, { right: true }, h('div', { style: { display: 'flex', gap: 6, justifyContent: 'flex-end' } }, h(IconBtn, { icon: 'edit' }), h(IconBtn, { icon: 'eye' }))))))));
  }

  // ---------------- Gallery ----------------
  function GalleryMgmt() {
    const cats = window.DATA.galleryCats;
    const [tab, setTab] = useState(cats[0].id);
    const active = cats.find(c => c.id === tab);
    const total = cats.reduce((n, c) => n + c.items.length, 0);
    return h('div', null,
      h(PageTitle, { title: 'Gallery', sub: total + ' images live · ' + cats.length + ' categories', actions: h('button', { className: 'btn btn-gold btn-sm' }, h(Icon, { name: 'plus', size: 16 }), 'Upload') }),
      h('div', { style: { display: 'flex', gap: 8, marginBottom: 20, flexWrap: 'wrap' } },
        cats.map(c => h('button', { key: c.id, onClick: () => setTab(c.id), style: { background: tab === c.id ? 'var(--gold-600)' : 'var(--ink-700)', color: tab === c.id ? '#211405' : 'var(--cream-2)', border: '1px solid ' + (tab === c.id ? 'var(--gold-600)' : 'var(--line)'), borderRadius: 999, padding: '9px 16px', cursor: 'pointer', fontSize: 13.5, fontWeight: 600, fontFamily: 'var(--sans)', display: 'flex', gap: 8, alignItems: 'center' } }, c.name, h('span', { style: { fontSize: 11, opacity: .7 } }, c.items.length)))),
      h('div', { className: 'adm-gallery-grid', style: { display: 'grid', gridTemplateColumns: 'repeat(4,1fr)', gap: 14 } },
        h('button', { style: { aspectRatio: '1', border: '1.5px dashed var(--line)', borderRadius: 14, background: 'var(--ink-800)', color: 'var(--muted)', cursor: 'pointer', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', gap: 8, fontSize: 13.5, fontWeight: 600, fontFamily: 'var(--sans)' } }, h(Icon, { name: 'plus', size: 26 }), 'Add to ' + active.name),
        active.items.map((g, i) => h('div', { key: i, style: { position: 'relative', borderRadius: 14, overflow: 'hidden', aspectRatio: '1' } },
          h(Ph, { label: g, style: { width: '100%', height: '100%' } }),
          h('div', { style: { position: 'absolute', top: 8, right: 8, display: 'flex', gap: 6 } }, h(IconBtn, { icon: 'edit' }), h(IconBtn, { icon: 'trash' }))))));
  }

  // ---------------- Users ----------------
  function Users() {
    const A = window.ADMIN;
    const roleTone = { Owner: 'gold', Manager: 'purple', Chef: 'red', 'Front of house': 'blue', Marketing: 'green' };
    return h('div', null,
      h(PageTitle, { title: 'Users & roles', sub: A.users.filter(u => u.status === 'Active').length + ' active · ' + A.users.length + ' total', actions: h('button', { className: 'btn btn-gold btn-sm' }, h(Icon, { name: 'plus', size: 16 }), 'Invite user') }),
      h(Card, { pad: '8px 8px' },
        h(Table, { cols: [{ label: 'Member' }, { label: 'Role' }, { label: 'Email' }, { label: 'Status' }, { label: 'Last active' }, { label: '', right: true }] },
          A.users.map((u, i) => h('tr', { key: i, onMouseEnter: e => e.currentTarget.style.background = 'var(--ink-750)', onMouseLeave: e => e.currentTarget.style.background = 'transparent' },
            h(Td, null, h('div', { style: { display: 'flex', alignItems: 'center', gap: 12 } }, h('div', { style: { width: 38, height: 38, borderRadius: 999, background: 'linear-gradient(135deg, var(--gold-600), var(--spice-600))', display: 'grid', placeItems: 'center', color: '#fff', fontWeight: 700, fontSize: 14, fontFamily: 'var(--serif)' } }, u.name.split(' ').map(n => n[0]).join('')), h('span', { style: { fontWeight: 600, color: 'var(--cream)' } }, u.name))),
            h(Td, null, h(Badge, { tone: roleTone[u.role] }, u.role)),
            h(Td, null, h('span', { style: { fontSize: 13.5, color: 'var(--sand)' } }, u.email)),
            h(Td, null, h(Badge, { tone: u.status === 'Active' ? 'green' : u.status === 'Invited' ? 'gold' : 'neutral', dot: true }, u.status)),
            h(Td, null, h('span', { style: { fontSize: 13.5, color: 'var(--muted)' } }, u.last)),
            h(Td, { right: true }, h(IconBtn, { icon: 'dots' })))))),
      h('div', { className: 'adm-perm-grid', style: { display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: 16, marginTop: 20 } },
        [['Owner', 'Full access to everything, billing & integrations'], ['Manager', 'Orders, reservations, menu, content & reports'], ['Staff', 'View & update assigned orders and reservations']].map((r, i) => h(Card, { key: i },
          h('h4', { style: { fontSize: 17, fontWeight: 600, marginBottom: 6 } }, r[0]), h('p', { style: { fontSize: 13.5, color: 'var(--muted)', lineHeight: 1.6 } }, r[1])))));
  }

  // ---------------- Toast POS ----------------
  function Toast() {
    const t = window.ADMIN.toast;
    return h('div', null,
      h(PageTitle, { title: 'Toast POS integration', sub: t.location, actions: h('button', { className: 'btn btn-ghost btn-sm' }, h(Icon, { name: 'refresh', size: 16 }), 'Sync now') }),
      // status banner
      h(Card, { style: { display: 'flex', alignItems: 'center', gap: 18, marginBottom: 18, borderColor: 'var(--leaf-600)', background: 'linear-gradient(90deg, rgba(79,125,68,.1), var(--ink-700))' } },
        h('div', { style: { width: 52, height: 52, borderRadius: 13, background: 'rgba(79,125,68,.18)', border: '1px solid var(--leaf-600)', display: 'grid', placeItems: 'center', color: '#86b074', flexShrink: 0 } }, h(Icon, { name: 'link', size: 24 })),
        h('div', { style: { flex: 1 } }, h('div', { style: { display: 'flex', alignItems: 'center', gap: 10 } }, h('h3', { style: { fontSize: 19, fontWeight: 600 } }, 'Connected & healthy'), h(Badge, { tone: 'green', dot: true }, 'Live')), h('div', { style: { fontSize: 13.5, color: 'var(--muted)', marginTop: 3 } }, 'Last sync ' + t.lastSync + ' · auto-syncing every 2 minutes')),
        h('button', { className: 'btn btn-ghost btn-sm' }, h(Icon, { name: 'settings', size: 15 }), 'Configure')),
      h('div', { className: 'adm-stat-grid', style: { display: 'grid', gridTemplateColumns: 'repeat(4,1fr)', gap: 18, marginBottom: 18 } },
        [['Items synced', '64', 'fork', 'gold'], ['Orders pushed', '1,284', 'bag', 'green'], ['Avg. sync time', '0.8s', 'refresh', 'blue'], ['Conflicts (24h)', '0', 'check', 'green']].map((s, i) => h(Card, { key: i },
          h('div', { style: { display: 'flex', justifyContent: 'space-between' } }, h('span', { style: { fontSize: 13, color: 'var(--sand)' } }, s[0]), h('div', { style: { width: 34, height: 34, borderRadius: 9, background: window.AW.toneMap[s[3]][0], display: 'grid', placeItems: 'center', color: window.AW.toneMap[s[3]][1] } }, h(Icon, { name: s[2], size: 17 }))),
          h('div', { style: { fontFamily: 'var(--serif)', fontSize: 30, fontWeight: 600, marginTop: 12 } }, s[1])))),
      h('div', { className: 'adm-toast-grid', style: { display: 'grid', gridTemplateColumns: '1.4fr 1fr', gap: 18, alignItems: 'start' } },
        h(Card, { pad: 0 },
          h('div', { style: { padding: '18px 22px', borderBottom: '1px solid var(--line)' } }, h('h3', { style: { fontSize: 18, fontWeight: 600 } }, 'Sync status by data type')),
          h(Table, { cols: [{ label: 'Data' }, { label: 'Direction' }, { label: 'Records', right: true }, { label: 'Status' }, { label: 'Last' }] },
            t.syncs.map((s, i) => h('tr', { key: i }, h(Td, null, h('span', { style: { fontWeight: 600, color: 'var(--cream)' } }, s.type)),
              h(Td, null, h('span', { style: { fontFamily: 'ui-monospace,monospace', fontSize: 12.5, color: 'var(--sand)' } }, s.dir)),
              h(Td, { right: true }, s.count.toLocaleString()),
              h(Td, null, h(Badge, { tone: s.status === 'Synced' ? 'green' : 'gold', dot: true }, s.status)),
              h(Td, null, h('span', { style: { fontSize: 13, color: 'var(--muted)' } }, s.time))))) ),
        h(Card, { pad: 0 },
          h('div', { style: { padding: '18px 22px', borderBottom: '1px solid var(--line)' } }, h('h3', { style: { fontSize: 18, fontWeight: 600 } }, 'Activity log')),
          h('div', { style: { padding: 18 } },
            t.log.map((l, i) => h('div', { key: i, style: { display: 'flex', gap: 12, paddingBottom: 16 } },
              h('div', { style: { display: 'flex', flexDirection: 'column', alignItems: 'center' } }, h('span', { style: { width: 9, height: 9, borderRadius: 99, background: l.ok ? 'var(--leaf-500)' : 'var(--gold-500)', marginTop: 4, flexShrink: 0 } }), i < t.log.length - 1 && h('span', { style: { width: 1, flex: 1, background: 'var(--line)', marginTop: 4 } })),
              h('div', null, h('div', { style: { fontSize: 13.5, color: 'var(--cream-2)', lineHeight: 1.5 } }, l.m), h('div', { style: { fontSize: 12, color: 'var(--faint)', marginTop: 2 } }, l.t)))))) ));
  }

  // ---------------- Settings ----------------
  function Settings() {
    const [t, setT] = useState({ delivery: true, online: true, tips: true, sms: false });
    const block = (title, rows) => h(Card, { style: { marginBottom: 16 } },
      h('h3', { style: { fontSize: 18, fontWeight: 600, marginBottom: 4 } }, title),
      h('div', null, rows.map((r, i) => h('div', { key: i, style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '14px 0', borderBottom: i < rows.length - 1 ? '1px solid var(--line-soft)' : 'none' } },
        h('div', null, h('div', { style: { fontWeight: 600, fontSize: 14.5 } }, r.label), h('div', { style: { fontSize: 13, color: 'var(--muted)', marginTop: 2 } }, r.sub)),
        r.toggle ? h(Toggle, { on: t[r.key], onClick: () => setT(s => ({ ...s, [r.key]: !s[r.key] })) }) : h('span', { style: { color: 'var(--sand)', fontSize: 14 } }, r.value)))));
    return h('div', { style: { maxWidth: 760 } },
      h(PageTitle, { title: 'Settings', sub: 'Restaurant profile, ordering rules and notifications.' }),
      block('Restaurant profile', [{ label: 'Name', sub: 'Shown across the site', value: 'Indian Nepali Kitchen' }, { label: 'Address', sub: 'Primary location', value: '418 Saffron Lane' }, { label: 'Hours', sub: 'Service window', value: 'Tue–Sun · 11:30–22:00' }]),
      block('Ordering', [{ label: 'Online ordering', sub: 'Accept orders from the website', toggle: true, key: 'online' }, { label: 'Delivery', sub: 'Offer delivery within 4 miles', toggle: true, key: 'delivery' }, { label: 'Allow tips', sub: 'Show tip options at checkout', toggle: true, key: 'tips' }]),
      block('Notifications', [{ label: 'SMS order alerts', sub: 'Text the kitchen on new orders', toggle: true, key: 'sms' }]));
  }

  // ---------------- Gift cards ----------------
  function GiftCards() {
    const A = window.ADMIN;
    const [tab, setTab] = useState('All');
    const [q, setQ] = useState('');
    const tabs = ['All', 'Active', 'Partially used', 'Redeemed'];
    const rows = A.giftCards.filter(g => (tab === 'All' || g.status === tab) && (!q || (g.code + g.recipient).toLowerCase().includes(q.toLowerCase())));
    const gtone = { Active: 'green', 'Partially used': 'gold', Redeemed: 'neutral' };
    return h('div', null,
      h(PageTitle, { title: 'Gift cards', sub: A.giftStats.active + ' active cards · ' + A.giftStats.outstanding + ' outstanding balance', actions: h('button', { className: 'btn btn-gold btn-sm' }, h(Icon, { name: 'plus', size: 16 }), 'Issue card') }),
      h('div', { className: 'adm-stat-grid', style: { display: 'grid', gridTemplateColumns: 'repeat(4,1fr)', gap: 18, marginBottom: 18 } },
        h(window.AW.StatCard, { label: 'Sold (30d)', value: A.giftStats.sold, delta: '+18%', up: true, icon: 'tag', tone: 'gold', spark: A.giftSales }),
        h(window.AW.StatCard, { label: 'Outstanding balance', value: A.giftStats.outstanding, icon: 'dollar', tone: 'blue' }),
        h(window.AW.StatCard, { label: 'Active cards', value: A.giftStats.active, icon: 'box', tone: 'green' }),
        h(window.AW.StatCard, { label: 'Redeemed (30d)', value: A.giftStats.redeemed30, delta: '+9%', up: true, icon: 'check', tone: 'purple' })),
      h('div', { style: { display: 'flex', gap: 6, marginBottom: 18, borderBottom: '1px solid var(--line)' } },
        tabs.map(t => h('button', { key: t, onClick: () => setTab(t), style: { background: 'none', border: 'none', borderBottom: '2px solid ' + (tab === t ? 'var(--gold-500)' : 'transparent'), color: tab === t ? 'var(--cream)' : 'var(--muted)', cursor: 'pointer', padding: '10px 14px', fontSize: 14, fontWeight: 600, fontFamily: 'var(--sans)', marginBottom: -1 } }, t))),
      h(Search, { value: q, onChange: setQ, ph: 'Search code or recipient…' }),
      h(Card, { pad: '8px 8px' },
        h(Table, { cols: [{ label: 'Code' }, { label: 'Design' }, { label: 'Recipient' }, { label: 'Channel' }, { label: 'Face value', right: true }, { label: 'Balance', right: true }, { label: 'Status' }, { label: 'Issued' }] },
          rows.map(g => h('tr', { key: g.code, onMouseEnter: e => e.currentTarget.style.background = 'var(--ink-750)', onMouseLeave: e => e.currentTarget.style.background = 'transparent' },
            h(Td, null, h('span', { style: { fontFamily: 'ui-monospace,monospace', fontSize: 13, fontWeight: 600, color: 'var(--cream)' } }, g.code)),
            h(Td, null, h('span', { style: { fontSize: 13.5, color: 'var(--sand)' } }, g.design)),
            h(Td, null, g.recipient),
            h(Td, null, h(Badge, { tone: 'neutral' }, g.channel)),
            h(Td, { right: true }, '$' + g.face),
            h(Td, { right: true }, h('span', { style: { fontWeight: 600, fontFamily: 'var(--serif)', fontSize: 16, color: g.balance > 0 ? 'var(--gold-400)' : 'var(--faint)' } }, '$' + g.balance)),
            h(Td, null, h(Badge, { tone: gtone[g.status], dot: true }, g.status)),
            h(Td, null, h('span', { style: { fontSize: 13, color: 'var(--muted)' } }, g.issued.slice(5))))))));
  }

  Object.assign(window, { AdminCatering: Catering, AdminContact: Contact, AdminContentCMS: ContentCMS, AdminGallery: GalleryMgmt, AdminUsers: Users, AdminToast: Toast, AdminSettings: Settings, AdminGiftCards: GiftCards });
})();

/* Admin shell: AdminContext, Sidebar, Topbar → window */
(function () {
  const { useState, useContext } = React;
  const h = React.createElement;
  const Icon = window.Icon, Logo = window.Logo;

  const AdminContext = React.createContext(null);
  window.AdminContext = AdminContext;

  const NAV = [
    { group: 'Operations', items: [
      { id: 'overview', label: 'Overview', icon: 'grid' },
      { id: 'orders', label: 'Orders', icon: 'bag', badge: 3 },
      { id: 'reservations', label: 'Reservations', icon: 'cal', badge: 5 },
      { id: 'catering', label: 'Catering', icon: 'box', badge: 3 },
      { id: 'contact', label: 'Inquiries', icon: 'mail', badge: 4 },
    ]},
    { group: 'Content', items: [
      { id: 'menu', label: 'Menu', icon: 'fork' },
      { id: 'content', label: 'Website content', icon: 'file' },
      { id: 'gallery', label: 'Gallery', icon: 'image' },
      { id: 'giftcards', label: 'Gift cards', icon: 'tag' },
    ]},
    { group: 'System', items: [
      { id: 'toast', label: 'Toast POS', icon: 'refresh' },
      { id: 'users', label: 'Users & roles', icon: 'users' },
      { id: 'settings', label: 'Settings', icon: 'settings' },
    ]},
  ];
  window.ADMIN_NAV = NAV;

  function Sidebar() {
    const { view, go, sidebarOpen, setSidebarOpen } = useContext(AdminContext);
    const item = (it) => h('button', { key: it.id, onClick: () => { go(it.id); setSidebarOpen(false); },
      style: { display: 'flex', alignItems: 'center', gap: 12, width: '100%', textAlign: 'left', border: 'none', cursor: 'pointer', padding: '10px 12px', borderRadius: 10, marginBottom: 2, fontSize: 14.5, fontWeight: 500, fontFamily: 'var(--sans)', position: 'relative',
        background: view === it.id ? 'linear-gradient(90deg, var(--gold-glow), transparent)' : 'transparent',
        color: view === it.id ? 'var(--gold-400)' : 'var(--sand)', transition: 'all .15s' },
      onMouseEnter: e => { if (view !== it.id) { e.currentTarget.style.background = 'var(--ink-700)'; e.currentTarget.style.color = 'var(--cream)'; } },
      onMouseLeave: e => { if (view !== it.id) { e.currentTarget.style.background = 'transparent'; e.currentTarget.style.color = 'var(--sand)'; } } },
      view === it.id && h('span', { style: { position: 'absolute', left: -16, top: '50%', transform: 'translateY(-50%)', width: 3, height: 20, borderRadius: 3, background: 'var(--gold-500)' } }),
      h(Icon, { name: it.icon, size: 18, stroke: view === it.id ? 2 : 1.7 }),
      h('span', { style: { flex: 1 } }, it.label),
      it.badge && h('span', { style: { background: view === it.id ? 'var(--gold-600)' : 'var(--ink-600)', color: view === it.id ? '#211405' : 'var(--sand)', fontSize: 11, fontWeight: 700, minWidth: 19, height: 19, borderRadius: 999, display: 'grid', placeItems: 'center', padding: '0 5px' } }, it.badge));

    return h(React.Fragment, null,
      sidebarOpen && h('div', { onClick: () => setSidebarOpen(false), className: 'adm-scrim', style: { position: 'fixed', inset: 0, background: 'rgba(0,0,0,.6)', zIndex: 90, display: 'none' } }),
      h('aside', { className: 'adm-sidebar' + (sidebarOpen ? ' open' : ''), style: { width: 260, flexShrink: 0, background: 'var(--ink-850)', borderRight: '1px solid var(--line)', height: '100vh', position: 'sticky', top: 0, display: 'flex', flexDirection: 'column', zIndex: 95 } },
        h('div', { style: { padding: '22px 22px 18px', borderBottom: '1px solid var(--line)' } }, h(Logo, { size: 30 })),
        h('div', { style: { flex: 1, overflowY: 'auto', padding: '18px 16px' } },
          NAV.map(g => h('div', { key: g.group, style: { marginBottom: 22 } },
            h('div', { style: { fontSize: 11, fontWeight: 700, letterSpacing: '.14em', textTransform: 'uppercase', color: 'var(--faint)', padding: '0 12px 10px' } }, g.group),
            g.items.map(item)))),
        h('div', { style: { padding: 16, borderTop: '1px solid var(--line)' } },
          h('div', { style: { display: 'flex', alignItems: 'center', gap: 11, padding: '8px 6px' } },
            h('div', { style: { width: 38, height: 38, borderRadius: 999, background: 'linear-gradient(135deg, var(--gold-600), var(--spice-600))', display: 'grid', placeItems: 'center', color: '#fff', fontWeight: 700, fontSize: 15, fontFamily: 'var(--serif)' } }, 'SA'),
            h('div', { style: { flex: 1, minWidth: 0 } }, h('div', { style: { fontSize: 14, fontWeight: 600, color: 'var(--cream)' } }, 'Suman A.'), h('div', { style: { fontSize: 12, color: 'var(--muted)' } }, 'Owner')),
            h('button', { onClick: () => go('login'), title: 'Sign out', style: { background: 'none', border: 'none', color: 'var(--muted)', cursor: 'pointer', display: 'grid', placeItems: 'center' } }, h(Icon, { name: 'logout', size: 18 }))))));
  }

  function Topbar() {
    const { setSidebarOpen, title } = useContext(AdminContext);
    return h('header', { style: { position: 'sticky', top: 0, zIndex: 80, height: 70, background: 'rgba(18,14,11,.85)', backdropFilter: 'blur(12px)', borderBottom: '1px solid var(--line)', display: 'flex', alignItems: 'center', gap: 16, padding: '0 28px' } },
      h('button', { className: 'adm-burger', onClick: () => setSidebarOpen(true), style: { display: 'none', background: 'none', border: '1px solid var(--line)', borderRadius: 9, width: 40, height: 40, placeItems: 'center', cursor: 'pointer', color: 'var(--cream)' } }, h(Icon, { name: 'menu', size: 20 })),
      h('div', { style: { display: 'flex', alignItems: 'center', gap: 10, background: 'var(--ink-800)', border: '1px solid var(--line)', borderRadius: 10, padding: '0 14px', width: 320, maxWidth: '40vw' } },
        h(Icon, { name: 'search', size: 17, color: 'var(--muted)' }),
        h('input', { placeholder: 'Search orders, guests, dishes…', style: { flex: 1, background: 'none', border: 'none', outline: 'none', color: 'var(--cream)', fontSize: 14, padding: '11px 0', fontFamily: 'var(--sans)' } })),
      h('div', { style: { marginLeft: 'auto', display: 'flex', alignItems: 'center', gap: 12 } },
        h('a', { href: 'Indian Nepali Kitchen.html', target: '_blank', className: 'btn btn-ghost btn-sm', style: { textDecoration: 'none' } }, h(Icon, { name: 'eye', size: 16 }), 'View site'),
        h('button', { style: { position: 'relative', width: 42, height: 42, borderRadius: 10, background: 'var(--ink-800)', border: '1px solid var(--line)', color: 'var(--cream)', cursor: 'pointer', display: 'grid', placeItems: 'center' } }, h(Icon, { name: 'bell', size: 19 }), h('span', { style: { position: 'absolute', top: 8, right: 9, width: 8, height: 8, borderRadius: 999, background: 'var(--spice-500)', border: '2px solid var(--ink-800)' } }))));
  }

  window.Sidebar = Sidebar; window.Topbar = Topbar;
})();

/* Admin app root — routing + context */
(function () {
  const { useState, useEffect, useCallback } = React;
  const h = React.createElement;

  const TITLES = { overview: 'Overview', orders: 'Orders', reservations: 'Reservations', catering: 'Catering', contact: 'Inquiries', menu: 'Menu', content: 'Content', gallery: 'Gallery', giftcards: 'Gift cards', toast: 'Toast POS', users: 'Users', settings: 'Settings' };

  function App() {
    const [view, setView] = useState(() => location.hash.replace('#', '') || 'overview');
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const go = useCallback((v) => { setView(v); location.hash = v; const m = document.getElementById('adm-main'); if (m) m.scrollTo({ top: 0 }); }, []);
    useEffect(() => { const onHash = () => setView(location.hash.replace('#', '') || 'overview'); window.addEventListener('hashchange', onHash); return () => window.removeEventListener('hashchange', onHash); }, []);

    const ctx = { view, go, sidebarOpen, setSidebarOpen, title: TITLES[view] };
    const pages = {
      overview: window.AdminOverview, orders: window.AdminOrders, reservations: window.AdminReservations,
      catering: window.AdminCatering, contact: window.AdminContact, menu: window.AdminMenu,
      content: window.AdminContentCMS, gallery: window.AdminGallery, toast: window.AdminToast,
      users: window.AdminUsers, settings: window.AdminSettings, giftcards: window.AdminGiftCards,
    };
    const Page = pages[view] || window.AdminOverview;

    return h(window.AdminContext.Provider, { value: ctx },
      h('div', { style: { display: 'flex', minHeight: '100vh', background: 'var(--ink-800)' } },
        h(window.Sidebar, null),
        h('div', { style: { flex: 1, minWidth: 0, display: 'flex', flexDirection: 'column' } },
          h(window.Topbar, null),
          h('main', { id: 'adm-main', key: view, className: 'fade-up', style: { flex: 1, padding: '30px 32px 60px', maxWidth: 1400, width: '100%', margin: '0 auto' } },
            h(Page, null)))));
  }

  ReactDOM.createRoot(document.getElementById('root')).render(h(App));
})();

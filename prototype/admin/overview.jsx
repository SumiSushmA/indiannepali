/* Admin overview / analytics → window.AdminOverview */
(function () {
  const { useContext, useState } = React;
  const h = React.createElement;
  const Icon = window.Icon;
  const { Card, StatCard, BarChart, Donut, PageTitle, Segmented, Badge } = window.AW;

  function AdminOverview() {
    const A = window.ADMIN;
    const { go } = useContext(window.AdminContext);
    const [range, setRange] = useState('7 days');
    const newOrders = A.orders.filter(o => o.status === 'New' || o.status === 'Preparing');

    return h('div', null,
      h(PageTitle, { title: 'Good afternoon, Suman', sub: 'Tuesday, June 2 · here\u2019s how the Kitchen is doing today.', actions: h(Segmented, { options: ['Today', '7 days', '30 days'], value: range, onChange: setRange }) }),
      // stat row
      h('div', { className: 'adm-stat-grid', style: { display: 'grid', gridTemplateColumns: 'repeat(4,1fr)', gap: 18, marginBottom: 18 } },
        h(StatCard, { label: 'Revenue (7d)', value: '$31,510', delta: '+12.4%', up: true, icon: 'dollar', tone: 'gold', spark: A.revenue7 }),
        h(StatCard, { label: 'Orders (7d)', value: '486', delta: '+8.1%', up: true, icon: 'bag', tone: 'green', spark: [54, 48, 61, 66, 88, 102, 67] }),
        h(StatCard, { label: 'Covers booked', value: '312', delta: '+5.6%', up: true, icon: 'cal', tone: 'blue', spark: [38, 41, 36, 44, 58, 72, 49] }),
        h(StatCard, { label: 'Avg. order value', value: '$48.20', delta: '−2.0%', up: false, icon: 'trend', tone: 'red', spark: [52, 50, 49, 51, 47, 46, 48] })),
      // main grid
      h('div', { className: 'adm-over-grid', style: { display: 'grid', gridTemplateColumns: '2fr 1fr', gap: 18, marginBottom: 18 } },
        h(Card, { pad: 24 },
          h('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 4 } },
            h('div', null, h('h3', { style: { fontSize: 19, fontWeight: 600 } }, 'Revenue by day'), h('div', { style: { fontSize: 13, color: 'var(--muted)', marginTop: 3 } }, 'Last 7 days · all channels')),
            h(Badge, { tone: 'green', dot: true }, 'Up 12.4%')),
          h(BarChart, { data: A.revenue7, labels: A.revenueDays })),
        h(Card, { pad: 24 },
          h('h3', { style: { fontSize: 19, fontWeight: 600, marginBottom: 18 } }, 'Order channels'),
          h(Donut, { data: A.channelSplit }))),
      h('div', { className: 'adm-over-grid', style: { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 18 } },
        // live orders
        h(Card, { pad: 0 },
          h('div', { style: { padding: '20px 24px', borderBottom: '1px solid var(--line)', display: 'flex', justifyContent: 'space-between', alignItems: 'center' } },
            h('h3', { style: { fontSize: 19, fontWeight: 600 } }, 'Live orders'),
            h('button', { className: 'btn btn-ghost btn-sm', onClick: () => go('orders') }, 'View all', h(Icon, { name: 'arrow', size: 15 }))),
          h('div', { style: { padding: '6px 12px' } },
            newOrders.slice(0, 5).map(o => h('div', { key: o.id, style: { display: 'flex', alignItems: 'center', gap: 12, padding: '12px' } },
              h('div', { style: { width: 40, height: 40, borderRadius: 10, background: 'var(--ink-800)', display: 'grid', placeItems: 'center', color: 'var(--gold-400)', flexShrink: 0 } }, h(Icon, { name: o.type === 'Delivery' ? 'truck' : 'bag', size: 18 })),
              h('div', { style: { flex: 1, minWidth: 0 } }, h('div', { style: { fontWeight: 600, fontSize: 14.5 } }, o.id + ' · ' + o.customer), h('div', { style: { fontSize: 13, color: 'var(--muted)' } }, o.items.reduce((n, i) => n + i.qty, 0) + ' items · ' + o.time)),
              h('div', { style: { textAlign: 'right' } }, h('div', { style: { fontWeight: 600, color: 'var(--gold-400)', fontFamily: 'var(--serif)', fontSize: 16 } }, '$' + o.total), h(Badge, { tone: o.status === 'New' ? 'gold' : 'blue' }, o.status)))))),
        // top items
        h(Card, { pad: 0 },
          h('div', { style: { padding: '20px 24px', borderBottom: '1px solid var(--line)' } }, h('h3', { style: { fontSize: 19, fontWeight: 600 } }, 'Top dishes this week')),
          h('div', { style: { padding: '12px 24px' } },
            A.topItems.map((it, i) => { const max = A.topItems[0].sold; return h('div', { key: i, style: { padding: '11px 0', borderBottom: i < 4 ? '1px solid var(--line-soft)' : 'none' } },
              h('div', { style: { display: 'flex', justifyContent: 'space-between', marginBottom: 7, fontSize: 14 } }, h('span', { style: { fontWeight: 600 } }, h('span', { style: { color: 'var(--faint)', marginRight: 8 } }, '0' + (i + 1)), it.name), h('span', { style: { color: 'var(--sand)' } }, it.sold + ' sold · $' + it.rev.toLocaleString())),
              h('div', { style: { height: 6, background: 'var(--ink-800)', borderRadius: 99 } }, h('div', { style: { width: (it.sold / max * 100) + '%', height: '100%', borderRadius: 99, background: 'linear-gradient(90deg, var(--gold-700), var(--gold-500))' } }))); })))),
      // tasks strip
      h('div', { className: 'adm-task-grid', style: { display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: 18, marginTop: 18 } },
        taskCard('cal', 'gold', '5 reservations', 'need confirmation for tonight', () => go('reservations')),
        taskCard('box', 'purple', '3 catering inquiries', 'awaiting a quote', () => go('catering')),
        taskCard('mail', 'red', '4 unread messages', 'in the contact inbox', () => go('contact'))));
  }

  function taskCard(icon, tone, title, sub, onClick) {
    const t = window.AW.toneMap[tone];
    return h(window.AW.Card, { onClick, style: { cursor: 'pointer', display: 'flex', alignItems: 'center', gap: 16, transition: 'border-color .2s, transform .2s' }, onMouseEnter: e => { e.currentTarget.style.borderColor = 'var(--ink-600)'; e.currentTarget.style.transform = 'translateY(-2px)'; }, onMouseLeave: e => { e.currentTarget.style.borderColor = 'var(--line)'; e.currentTarget.style.transform = 'none'; } },
      h('div', { style: { width: 46, height: 46, borderRadius: 12, background: t[0], display: 'grid', placeItems: 'center', color: t[1], flexShrink: 0 } }, h(Icon, { name: icon, size: 22 })),
      h('div', { style: { flex: 1 } }, h('div', { style: { fontWeight: 600, fontSize: 16 } }, title), h('div', { style: { fontSize: 13.5, color: 'var(--muted)' } }, sub)),
      h(Icon, { name: 'arrow', size: 18, color: 'var(--muted)' }));
  }

  window.AdminOverview = AdminOverview;
})();

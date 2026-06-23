/* Admin shared widgets → window.AW */
(function () {
  const { useState } = React;
  const h = React.createElement;
  const Icon = window.Icon;

  const toneMap = {
    gold: ['rgba(200,133,47,.16)', 'var(--gold-400)', 'var(--gold-700)'],
    green: ['rgba(79,125,68,.16)', '#86b074', 'var(--leaf-600)'],
    red: ['rgba(156,59,37,.18)', '#d4795f', 'var(--spice-700)'],
    blue: ['rgba(90,130,180,.16)', '#9bb6d8', '#3c5878'],
    neutral: ['rgba(138,124,105,.14)', 'var(--sand)', 'var(--line)'],
    purple: ['rgba(150,110,160,.16)', '#c39bce', '#6a4a72'],
  };
  function Badge({ tone = 'neutral', children, dot }) {
    const [bg, fg, bd] = toneMap[tone] || toneMap.neutral;
    return h('span', { style: { display: 'inline-flex', alignItems: 'center', gap: 6, background: bg, color: fg, border: '1px solid ' + bd, fontSize: 12, fontWeight: 600, padding: '4px 10px', borderRadius: 999, whiteSpace: 'nowrap', letterSpacing: '.01em' } },
      dot && h('span', { style: { width: 6, height: 6, borderRadius: 999, background: fg } }), children);
  }

  function Card({ children, style, pad = 22, ...rest }) {
    return h('div', { style: { background: 'var(--ink-700)', border: '1px solid var(--line)', borderRadius: 16, padding: pad, ...style }, ...rest }, children);
  }

  function StatCard({ label, value, delta, up, icon, tone = 'gold', spark }) {
    const fg = (toneMap[tone] || toneMap.gold)[1];
    return h(Card, { style: { display: 'flex', flexDirection: 'column', gap: 14 } },
      h('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' } },
        h('div', { style: { fontSize: 13, color: 'var(--sand)', fontWeight: 500 } }, label),
        h('div', { style: { width: 38, height: 38, borderRadius: 10, background: (toneMap[tone] || toneMap.gold)[0], display: 'grid', placeItems: 'center', color: fg } }, h(Icon, { name: icon, size: 19 }))),
      h('div', { style: { display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', gap: 10 } },
        h('div', null,
          h('div', { style: { fontFamily: 'var(--serif)', fontSize: 32, fontWeight: 600, lineHeight: 1, color: 'var(--cream)' } }, value),
          delta != null && h('div', { style: { display: 'flex', alignItems: 'center', gap: 5, marginTop: 8, fontSize: 12.5, fontWeight: 600, color: up ? '#86b074' : '#d4795f' } }, h(Icon, { name: up ? 'up' : 'down', size: 14 }), delta, h('span', { style: { color: 'var(--muted)', fontWeight: 400 } }, 'vs last wk'))),
        spark && h(Sparkline, { data: spark, color: fg })));
  }

  function Sparkline({ data, color = 'var(--gold-500)', w = 84, hgt = 34 }) {
    const max = Math.max(...data), min = Math.min(...data);
    const pts = data.map((v, i) => [i / (data.length - 1) * w, hgt - (v - min) / (max - min || 1) * (hgt - 4) - 2]);
    const d = pts.map((p, i) => (i ? 'L' : 'M') + p[0].toFixed(1) + ' ' + p[1].toFixed(1)).join(' ');
    return h('svg', { width: w, height: hgt, style: { overflow: 'visible' } },
      h('path', { d: d + ` L${w} ${hgt} L0 ${hgt} Z`, fill: color, opacity: .12 }),
      h('path', { d, fill: 'none', stroke: color, strokeWidth: 2, strokeLinecap: 'round', strokeLinejoin: 'round' }),
      h('circle', { cx: pts[pts.length - 1][0], cy: pts[pts.length - 1][1], r: 2.8, fill: color }));
  }

  function BarChart({ data, labels, height = 200, accent = 'var(--gold-600)' }) {
    const max = Math.max(...data);
    const [hover, setHover] = useState(-1);
    return h('div', { style: { display: 'flex', alignItems: 'flex-end', gap: 10, height, paddingTop: 24 } },
      data.map((v, i) => h('div', { key: i, onMouseEnter: () => setHover(i), onMouseLeave: () => setHover(-1), style: { flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', height: '100%', justifyContent: 'flex-end', cursor: 'default' } },
        h('div', { style: { fontSize: 12, fontWeight: 600, color: hover === i ? 'var(--gold-400)' : 'var(--sand)', marginBottom: 8, opacity: hover === i ? 1 : .85 } }, '$' + (v / 1000).toFixed(1) + 'k'),
        h('div', { style: { width: '100%', maxWidth: 46, height: (v / max * 100) + '%', minHeight: 4, borderRadius: '7px 7px 3px 3px', background: hover === i ? 'linear-gradient(180deg, var(--gold-400), var(--gold-600))' : 'linear-gradient(180deg, var(--gold-600), var(--gold-700))', transition: 'all .2s', boxShadow: hover === i ? '0 0 18px rgba(200,133,47,.4)' : 'none' } }),
        h('div', { style: { fontSize: 12, color: 'var(--muted)', marginTop: 10, fontWeight: 500 } }, labels[i]))));
  }

  function AreaChart({ data, height = 80, color = 'var(--spice-500)' }) {
    const w = 300, max = Math.max(...data), min = 0;
    const pts = data.map((v, i) => [i / (data.length - 1) * w, height - (v - min) / (max - min || 1) * (height - 8) - 4]);
    const line = pts.map((p, i) => (i ? 'L' : 'M') + p[0].toFixed(1) + ' ' + p[1].toFixed(1)).join(' ');
    return h('svg', { viewBox: `0 0 ${w} ${height}`, preserveAspectRatio: 'none', style: { width: '100%', height, display: 'block' } },
      h('defs', null, h('linearGradient', { id: 'ag', x1: 0, y1: 0, x2: 0, y2: 1 }, h('stop', { offset: '0%', stopColor: color, stopOpacity: .35 }), h('stop', { offset: '100%', stopColor: color, stopOpacity: 0 }))),
      h('path', { d: line + ` L${w} ${height} L0 ${height} Z`, fill: 'url(#ag)' }),
      h('path', { d: line, fill: 'none', stroke: color, strokeWidth: 2.5, strokeLinejoin: 'round' }));
  }

  function Donut({ data, size = 160, thickness = 22 }) {
    const total = data.reduce((s, d) => s + d.value, 0);
    const r = (size - thickness) / 2, c = 2 * Math.PI * r;
    let acc = 0;
    return h('div', { style: { display: 'flex', alignItems: 'center', gap: 24, flexWrap: 'wrap' } },
      h('div', { style: { position: 'relative', width: size, height: size, flexShrink: 0 } },
        h('svg', { width: size, height: size, style: { transform: 'rotate(-90deg)' } },
          h('circle', { cx: size / 2, cy: size / 2, r, fill: 'none', stroke: 'var(--ink-600)', strokeWidth: thickness }),
          data.map((d, i) => { const len = d.value / total * c; const el = h('circle', { key: i, cx: size / 2, cy: size / 2, r, fill: 'none', stroke: d.color, strokeWidth: thickness, strokeDasharray: `${len} ${c - len}`, strokeDashoffset: -acc, strokeLinecap: 'butt' }); acc += len; return el; })),
        h('div', { style: { position: 'absolute', inset: 0, display: 'grid', placeItems: 'center', textAlign: 'center' } }, h('div', null, h('div', { style: { fontFamily: 'var(--serif)', fontSize: 26, fontWeight: 600 } }, total + '%'), h('div', { style: { fontSize: 11, color: 'var(--muted)', textTransform: 'uppercase', letterSpacing: '.1em' } }, 'Channels')))),
      h('div', { style: { display: 'flex', flexDirection: 'column', gap: 11, flex: 1, minWidth: 150 } },
        data.map((d, i) => h('div', { key: i, style: { display: 'flex', alignItems: 'center', gap: 10, fontSize: 13.5 } },
          h('span', { style: { width: 11, height: 11, borderRadius: 3, background: d.color, flexShrink: 0 } }),
          h('span', { style: { color: 'var(--cream-2)', flex: 1 } }, d.label),
          h('span', { style: { fontWeight: 700, color: 'var(--cream)' } }, d.value + '%')))));
  }

  // generic table
  function Table({ cols, children }) {
    return h('div', { style: { overflowX: 'auto' } },
      h('table', { style: { width: '100%', borderCollapse: 'collapse', minWidth: 600 } },
        h('thead', null, h('tr', null, cols.map((c, i) => h('th', { key: i, style: { textAlign: c.right ? 'right' : 'left', fontSize: 11.5, fontWeight: 700, letterSpacing: '.08em', textTransform: 'uppercase', color: 'var(--muted)', padding: '0 16px 14px', whiteSpace: 'nowrap' } }, c.label)))),
        h('tbody', null, children)));
  }
  const Td = ({ children, right, style }) => h('td', { style: { padding: '15px 16px', borderTop: '1px solid var(--line-soft)', fontSize: 14, color: 'var(--cream-2)', textAlign: right ? 'right' : 'left', verticalAlign: 'middle', ...style } }, children);

  function IconBtn({ icon, onClick, title }) {
    return h('button', { onClick, title, style: { width: 34, height: 34, borderRadius: 9, background: 'transparent', border: '1px solid var(--line)', color: 'var(--sand)', cursor: 'pointer', display: 'grid', placeItems: 'center', transition: 'all .18s' }, onMouseEnter: e => { e.currentTarget.style.color = 'var(--gold-400)'; e.currentTarget.style.borderColor = 'var(--gold-700)'; }, onMouseLeave: e => { e.currentTarget.style.color = 'var(--sand)'; e.currentTarget.style.borderColor = 'var(--line)'; } }, h(Icon, { name: icon, size: 16 }));
  }

  function PageTitle({ title, sub, actions }) {
    return h('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'flex-end', gap: 20, flexWrap: 'wrap', marginBottom: 26 } },
      h('div', null, h('h1', { style: { fontSize: 30, fontWeight: 600, letterSpacing: '-.01em' } }, title), sub && h('p', { style: { color: 'var(--muted)', fontSize: 14.5, marginTop: 6 } }, sub)),
      actions && h('div', { style: { display: 'flex', gap: 10, alignItems: 'center' } }, actions));
  }

  function Segmented({ options, value, onChange }) {
    return h('div', { style: { display: 'inline-flex', background: 'var(--ink-800)', border: '1px solid var(--line)', borderRadius: 10, padding: 3 } },
      options.map(o => h('button', { key: o, onClick: () => onChange(o), style: { border: 'none', background: value === o ? 'var(--ink-600)' : 'transparent', color: value === o ? 'var(--cream)' : 'var(--muted)', padding: '7px 14px', borderRadius: 7, cursor: 'pointer', fontWeight: 600, fontSize: 13, fontFamily: 'var(--sans)', transition: 'all .15s' } }, o)));
  }

  window.AW = { Badge, Card, StatCard, Sparkline, BarChart, AreaChart, Donut, Table, Td, IconBtn, PageTitle, Segmented, toneMap };
})();

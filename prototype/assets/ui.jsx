/* Shared UI primitives → window. Requires React + window.Icon */
(function () {
  const { useState } = React;
  const h = React.createElement;

  // Brand logo lockup — geometric mark (concentric circle + diamond) + wordmark
  function Logo({ size = 38, light = true, stacked = false, onClick, style = {} }) {
    const gold = '#d4a24e';
    const mark = h('svg', { width: size, height: size, viewBox: '0 0 48 48', style: { flexShrink: 0 } },
      h('circle', { cx: 24, cy: 24, r: 22, fill: 'none', stroke: gold, strokeWidth: 1.4, opacity: .55 }),
      h('circle', { cx: 24, cy: 24, r: 16.5, fill: 'none', stroke: gold, strokeWidth: 1 }),
      h('path', { d: 'M24 11 L33 24 L24 37 L15 24 Z', fill: 'none', stroke: gold, strokeWidth: 1.4 }),
      h('circle', { cx: 24, cy: 24, r: 4.4, fill: gold }),
    );
    const word = h('div', { style: { lineHeight: 1, display: stacked ? 'block' : 'flex', flexDirection: 'column' } },
      h('div', { style: { fontFamily: 'var(--serif)', fontWeight: 600, fontSize: size * .5, letterSpacing: '.02em', color: light ? 'var(--cream)' : '#1a120a' } }, 'Indian Nepali'),
      h('div', { style: { fontFamily: 'var(--sans)', fontWeight: 600, fontSize: size * .235, letterSpacing: '.42em', textTransform: 'uppercase', color: gold, marginTop: 3, paddingLeft: 2 } }, 'Kitchen'),
    );
    return h('div', { onClick, style: { display: 'flex', alignItems: 'center', gap: size * .3, cursor: onClick ? 'pointer' : 'default', ...style } }, mark, word);
  }

  // Striped placeholder with monospace label
  function Ph({ label, h: height = 'auto', r = 0, style = {}, className = '' }) {
    return h('div', { className: 'ph ' + className, style: { height, borderRadius: r, ...style } },
      h('span', null, label));
  }

  function Stars({ value = 5, size = 14 }) {
    return h('div', { style: { display: 'inline-flex', gap: 2, color: 'var(--gold-500)' } },
      [0, 1, 2, 3, 4].map(i => h(window.Icon, { key: i, name: 'star', size, fill: i < value ? 'currentColor' : 'none', stroke: 1.5, style: { opacity: i < value ? 1 : .3 } })));
  }

  window.Logo = Logo;
  window.Ph = Ph;
  window.Stars = Stars;
})();

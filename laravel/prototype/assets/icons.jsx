/* Shared icon set — simple line icons (Lucide-style paths). Exported to window.Icon */
(function () {
  const P = {
    menu: 'M3 6h18M3 12h18M3 18h18',
    x: 'M18 6 6 18M6 6l12 12',
    cart: 'M2.5 3h2l2.4 12.4a2 2 0 0 0 2 1.6h8.7a2 2 0 0 0 2-1.6L23 7H6',
    bag: 'M6 7V6a4 4 0 0 1 8 0v1M4 7h12l1 13H3L4 7Z',
    cal: 'M7 2v3M17 2v3M3 8h18M4 5h16a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z',
    clock: 'M12 7v5l3 2M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z',
    phone: 'M5 3h3l2 5-2.5 1.5a14 14 0 0 0 6 6L17 14l5 2v3a2 2 0 0 1-2 2A17 17 0 0 1 3 5a2 2 0 0 1 2-2Z',
    pin: 'M12 21s7-6.3 7-12a7 7 0 1 0-14 0c0 5.7 7 12 7 12Z M12 11.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z',
    star: 'M12 3.5l2.6 5.3 5.9.9-4.3 4.1 1 5.8-5.2-2.8L7.5 19.6l1-5.8L4.2 9.7l5.9-.9L12 3.5Z',
    arrow: 'M5 12h14M13 6l6 6-6 6',
    arrowL: 'M19 12H5M11 6l-6 6 6 6',
    plus: 'M12 5v14M5 12h14',
    minus: 'M5 12h14',
    check: 'M20 6 9 17l-5-5',
    search: 'M21 21l-4.3-4.3M11 18a7 7 0 1 0 0-14 7 7 0 0 0 0 14Z',
    bell: 'M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9M13.7 21a2 2 0 0 1-3.4 0',
    users: 'M16 19v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 9a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M22 19v-2a4 4 0 0 0-3-3.9M16 2.1a4 4 0 0 1 0 7.8',
    user: 'M20 21v-2a5 5 0 0 0-5-5H9a5 5 0 0 0-5 5v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z',
    grid: 'M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z',
    chart: 'M3 3v18h18M7 14v4M12 9v9M17 5v13',
    pie: 'M12 3v9l7.5 4.3A9 9 0 1 0 12 3Z',
    list: 'M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01',
    fork: 'M5 2v7a2 2 0 0 0 2 2v11M9 2v7a2 2 0 0 1-2 2M19 2c-1.5 0-3 1.5-3 5s1.5 4 3 4v11',
    truck: 'M3 6h11v9H3zM14 9h4l3 3v3h-7zM7 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4M18 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4',
    box: 'M21 8l-9-5-9 5 9 5 9-5ZM3 8v8l9 5 9-5V8M12 13v8',
    settings: 'M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6M19.4 15a1.6 1.6 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.6 1.6 0 0 0-2.7 1.1V21a2 2 0 1 1-4 0v-.1A1.6 1.6 0 0 0 7 19.4a1.6 1.6 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1A1.6 1.6 0 0 0 2.6 14H2a2 2 0 1 1 0-4h.1A1.6 1.6 0 0 0 4 7a1.6 1.6 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1A1.6 1.6 0 0 0 9 2.6V2a2 2 0 1 1 4 0v.1A1.6 1.6 0 0 0 17 4a1.6 1.6 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1A1.6 1.6 0 0 0 21.4 9H22a2 2 0 1 1 0 4h-.1a1.6 1.6 0 0 0-1.5 1Z',
    logout: 'M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9',
    edit: 'M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5Z',
    trash: 'M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6',
    eye: 'M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z',
    dots: 'M12 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2M19 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2M5 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2',
    filter: 'M3 4h18l-7 8v6l-4 2v-8L3 4Z',
    down: 'M6 9l6 6 6-6',
    up: 'M18 15l-6-6-6 6',
    mail: 'M3 5h18v14H3zM3 6l9 7 9-7',
    leaf: 'M11 20A7 7 0 0 1 4 13c0-5 4-9 16-9 0 9-4 13-9 13ZM4 21c1-4 4-7 9-9',
    flame: 'M12 2s5 4 5 10a5 5 0 0 1-10 0c0-1.5.5-2.5 1-3.5C8.5 11 9 13 11 13c0-3-1-5 1-11Z',
    tag: 'M20.6 13.4 12 22l-9-9V4a1 1 0 0 1 1-1h9l7.6 7.6a2 2 0 0 1 0 2.8ZM7.5 7.5h.01',
    sliders: 'M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6',
    home: 'M3 10.5 12 3l9 7.5M5 9v11h5v-6h4v6h5V9',
    image: 'M3 3h18v18H3zM3 16l5-5 4 4 3-3 6 6M9 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z',
    link: 'M9 15l6-6M11 6l1-1a4 4 0 0 1 6 6l-1 1M13 18l-1 1a4 4 0 0 1-6-6l1-1',
    refresh: 'M3 12a9 9 0 0 1 15-6.7L21 8M21 3v5h-5M21 12a9 9 0 0 1-15 6.7L3 16M3 21v-5h5',
    file: 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6ZM14 2v6h6',
    trend: 'M22 7l-8.5 8.5-4-4L2 19M16 7h6v6',
    dollar: 'M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6',
    wa: 'M3 21l1.6-5.5A8.5 8.5 0 1 1 8.5 19.4L3 21Z',
    ig: 'M3 7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4zM12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M17.5 6.5h.01',
    fb: 'M14 9V7a2 2 0 0 1 2-2h2V2h-3a5 5 0 0 0-5 5v2H8v3h2v9h3v-9h3l1-3h-4Z',
    spark: 'M12 2l1.6 6.4L20 10l-6.4 1.6L12 18l-1.6-6.4L4 10l6.4-1.6L12 2Z',
    info: 'M12 16v-4M12 8h.01M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z',
    veg: 'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Z',
  };

  function Icon({ name, size = 20, stroke = 1.75, fill = 'none', className = '', style = {}, color }) {
    const d = P[name];
    if (!d) return null;
    const filled = name === 'veg';
    return React.createElement('svg', {
      width: size, height: size, viewBox: '0 0 24 24',
      fill: filled ? (color || 'currentColor') : fill,
      stroke: filled ? 'none' : (color || 'currentColor'),
      strokeWidth: stroke, strokeLinecap: 'round', strokeLinejoin: 'round',
      className, style: { flexShrink: 0, ...style },
    }, React.createElement('path', { d }));
  }
  window.Icon = Icon;
})();

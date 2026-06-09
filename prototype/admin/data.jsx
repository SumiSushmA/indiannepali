/* Admin data → window.ADMIN */
(function () {
  const names = ['Asha Gurung', 'Marcus Lee', 'Priya Sharma', 'Dawa Sherpa', 'Elena Ruiz', 'Tom Becker', 'Nisha Rai', 'Omar Haddad', 'Grace Kim', 'Bikash Thapa', 'Sara Cohen', 'Liam Walsh', 'Maya Patel', 'Jon Park', 'Rita Magar'];
  const dishes = ['Chicken Momo', 'Butter Chicken', 'Jhol Momo', 'Nepali Thali', 'Hyderabadi Dum Biryani', 'Tandoori Chicken', 'Goat Curry', 'Dal Makhani', 'Garlic Naan', 'Gulab Jamun', 'Chicken Chilli', 'Thukpa'];
  const rnd = (a) => a[Math.floor(Math.random() * a.length)];

  // ---- Orders ----
  const orderStatuses = ['New', 'Preparing', 'Ready', 'Out for delivery', 'Completed'];
  const orders = Array.from({ length: 28 }).map((_, i) => {
    const itemsN = 1 + Math.floor(Math.random() * 4);
    const items = Array.from({ length: itemsN }).map(() => ({ name: rnd(dishes), qty: 1 + Math.floor(Math.random() * 2) }));
    const total = 14 + Math.floor(Math.random() * 80);
    const mins = Math.floor(Math.random() * 220);
    return {
      id: 'NK-' + (4850 - i), customer: rnd(names), type: Math.random() > .45 ? 'Delivery' : 'Pickup',
      status: i < 3 ? 'New' : i < 6 ? 'Preparing' : i < 8 ? 'Ready' : i < 11 ? 'Out for delivery' : 'Completed',
      items, total, channel: Math.random() > .4 ? 'Website' : (Math.random() > .5 ? 'Toast POS' : 'Phone'),
      time: mins < 60 ? mins + 'm ago' : Math.floor(mins / 60) + 'h ' + (mins % 60) + 'm ago', mins,
    };
  });

  // ---- Reservations ----
  const resStatuses = ['Confirmed', 'Seated', 'Pending', 'Cancelled', 'Completed'];
  const reservations = Array.from({ length: 22 }).map((_, i) => ({
    id: 'R-' + (2100 + i), name: rnd(names), party: 1 + Math.floor(Math.random() * 8),
    date: '2026-06-' + String(2 + Math.floor(Math.random() * 12)).padStart(2, '0'),
    time: rnd(['17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30']),
    status: rnd(resStatuses), table: 'T' + (1 + Math.floor(Math.random() * 18)),
    occasion: rnd(['—', '—', 'Birthday', 'Anniversary', 'Date night', 'Business']), phone: '(415) 555-0' + (100 + i),
  }));
  // calendar counts for June 2026
  const calCounts = {}; for (let d = 1; d <= 30; d++) calCounts[d] = Math.floor(Math.random() * 14);
  calCounts[3] = 8; calCounts[6] = 16; calCounts[7] = 19; calCounts[13] = 17; calCounts[14] = 21; calCounts[20] = 18;

  // ---- Catering inquiries ----
  const catering = Array.from({ length: 12 }).map((_, i) => ({
    id: 'C-' + (510 + i), name: rnd(names), event: rnd(['Wedding', 'Corporate', 'Birthday', 'Puja / religious', 'Other']),
    guests: rnd([30, 45, 60, 80, 120, 150, 200, 300]), date: '2026-0' + (6 + Math.floor(Math.random() * 3)) + '-' + String(2 + Math.floor(Math.random() * 25)).padStart(2, '0'),
    status: i < 3 ? 'New' : i < 6 ? 'Quoted' : i < 9 ? 'In conversation' : 'Booked',
    value: (2000 + Math.floor(Math.random() * 9000)), days: Math.floor(Math.random() * 6),
  }));

  // ---- Contact inquiries ----
  const contact = Array.from({ length: 14 }).map((_, i) => ({
    id: 'M-' + (880 + i), name: rnd(names), email: 'guest' + i + '@email.com',
    subject: rnd(['Lost item', 'Allergy question', 'Feedback', 'Gift cards', 'Private dining', 'Job application', 'Press inquiry']),
    status: i < 4 ? 'Unread' : i < 9 ? 'Open' : 'Resolved', days: Math.floor(Math.random() * 8),
    preview: rnd(['Hi, I left a scarf at table 6 last night…', 'Do your momo contain gluten? My son…', 'Wonderful dinner — please thank the chef!', 'Looking to buy a $100 gift card for…', 'Interested in the chef\u2019s counter for 8…']),
  }));

  // ---- Users / staff ----
  const users = [
    { name: 'Suman Adhikari', role: 'Owner', email: 'suman@indiannepali.kitchen', status: 'Active', last: '2m ago' },
    { name: 'Bishnu Karki', role: 'Manager', email: 'bishnu@indiannepali.kitchen', status: 'Active', last: '1h ago' },
    { name: 'Rekha Lama', role: 'Chef', email: 'rekha@indiannepali.kitchen', status: 'Active', last: 'Today' },
    { name: 'Daniel Ortiz', role: 'Front of house', email: 'daniel@indiannepali.kitchen', status: 'Active', last: 'Yesterday' },
    { name: 'Anita Shrestha', role: 'Marketing', email: 'anita@indiannepali.kitchen', status: 'Invited', last: '—' },
    { name: 'Kevin Wu', role: 'Front of house', email: 'kevin@indiannepali.kitchen', status: 'Inactive', last: '3 wks ago' },
  ];

  // ---- Analytics ----
  const revenue7 = [3120, 2890, 3450, 4210, 5680, 6920, 5240]; // by weekday
  const revenueDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
  const channelSplit = [{ label: 'Website', value: 46, color: 'var(--gold-600)' }, { label: 'Toast POS (dine-in)', value: 34, color: 'var(--spice-600)' }, { label: 'Phone', value: 12, color: '#6f9b5c' }, { label: 'Third-party', value: 8, color: '#5f5446' }];
  const topItems = [{ name: 'Chicken Momo', sold: 312, rev: 3744 }, { name: 'Butter Chicken', sold: 248, rev: 4712 }, { name: 'Nepali Thali', sold: 196, rev: 4704 }, { name: 'Jhol Momo', sold: 184, rev: 2576 }, { name: 'Dum Biryani', sold: 162, rev: 2916 }];
  const hourly = [2, 1, 0, 0, 0, 0, 0, 0, 0, 0, 3, 14, 22, 18, 9, 5, 4, 8, 19, 28, 24, 16, 7, 3];

  // ---- Toast POS integration ----
  const toast = {
    connected: true, location: 'Riverside District · Loc #RD-4471', lastSync: '2 min ago',
    syncs: [
      { type: 'Menu items', dir: 'POS → Web', count: 64, status: 'Synced', time: '2 min ago' },
      { type: 'Orders', dir: 'Web → POS', count: 1284, status: 'Synced', time: '2 min ago' },
      { type: 'Modifiers', dir: 'POS → Web', count: 38, status: 'Synced', time: '11 min ago' },
      { type: 'Inventory (86\u2019d items)', dir: 'POS → Web', count: 3, status: 'Synced', time: '11 min ago' },
      { type: 'Gift cards', dir: 'Two-way', count: 142, status: 'Synced', time: '1 hr ago' },
      { type: 'Payouts', dir: 'POS → Web', count: 7, status: 'Pending', time: '\u2014' },
    ],
    log: [
      { t: '14:32', m: 'Order NK-4850 pushed to POS · ticket #A192', ok: true },
      { t: '14:31', m: 'Menu sync complete · 64 items, 0 conflicts', ok: true },
      { t: '14:20', m: '"Goat Curry" marked 86\u2019d from POS — hidden on web', ok: true },
      { t: '13:58', m: 'Payout reconciliation pending — awaiting batch close', ok: false },
      { t: '13:40', m: 'Order NK-4848 fulfilled · synced to POS', ok: true },
    ],
  };

  // ---- Content / CMS blocks ----
  const content = [
    { section: 'Hero headline', value: 'Where the Himalayas meet the tandoor.', type: 'Text', updated: '3 days ago' },
    { section: 'Hero subtext', value: 'Hand-pleated momo, charcoal-fired kebabs…', type: 'Text', updated: '3 days ago' },
    { section: 'Hours banner', value: 'Tue–Sun · 11:30 – 22:00', type: 'Text', updated: '1 wk ago' },
    { section: 'Promo 1', value: 'Himalayan Lunch Thali — $14', type: 'Promotion', updated: 'Today' },
    { section: 'Promo 2', value: '20% off orders over $80', type: 'Promotion', updated: 'Today' },
    { section: 'Gallery', value: '12 images', type: 'Media', updated: '5 days ago' },
    { section: 'About story', value: 'Two kitchens, one table…', type: 'Text', updated: '2 wks ago' },
  ];

  // ---- Gift cards ----
  const gcNames = names;
  const giftCards = Array.from({ length: 16 }).map((_, i) => {
    const face = rnd([25, 50, 75, 100, 150, 250]);
    const used = Math.random();
    const bal = i < 3 ? face : (used > .7 ? 0 : Math.round(face * (1 - used) / 5) * 5);
    return {
      code: 'NK-' + Math.random().toString(36).slice(2, 6).toUpperCase() + '-' + (1000 + i),
      design: rnd(['Saffron Gold', 'Spice Market', 'Himalaya']), face, balance: bal,
      status: bal === 0 ? 'Redeemed' : (bal < face ? 'Partially used' : 'Active'),
      recipient: rnd(gcNames), channel: Math.random() > .3 ? 'Online' : 'In-store',
      issued: '2026-0' + (4 + Math.floor(Math.random() * 3)) + '-' + String(1 + Math.floor(Math.random() * 27)).padStart(2, '0'),
    };
  });
  const giftStats = { sold: '$8,420', outstanding: '$3,165', active: giftCards.filter(g => g.status !== 'Redeemed').length, redeemed30: '$2,940' };
  const giftSales = [620, 540, 880, 760, 1240, 1680, 980]; // last 7 days

  window.ADMIN = { orders, orderStatuses, reservations, resStatuses, calCounts, catering, contact, users, revenue7, revenueDays, channelSplit, topItems, hourly, toast, content, dishes, giftCards, giftStats, giftSales };
})();

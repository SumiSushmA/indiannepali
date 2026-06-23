/* Customer-facing data → window.DATA */
(function () {
  const menu = {
    categories: [
      { id: 'momo', name: 'Momo', tag: 'Nepali Dumplings', desc: 'Hand-pleated, steamed or fried, served with house achar' },
      { id: 'starters', name: 'Starters', tag: 'To Begin', desc: 'Tandoor-charred and griddle-crisped small plates' },
      { id: 'tandoor', name: 'From the Tandoor', tag: 'Clay Oven', desc: 'Marinated overnight, fired in our charcoal tandoor' },
      { id: 'curry', name: 'Curries', tag: 'House Classics', desc: 'Slow-simmered gravies, ground fresh each morning' },
      { id: 'nepali', name: 'Nepali Specials', tag: 'From the Hills', desc: 'Recipes carried from Kathmandu kitchens' },
      { id: 'biryani', name: 'Biryani & Rice', tag: 'Dum-Cooked', desc: 'Sealed and steamed with aged basmati' },
      { id: 'breads', name: 'Breads', tag: 'Tandoor Baked', desc: 'Pulled to order from the clay oven' },
      { id: 'dessert', name: 'Sweets', tag: 'To Finish', desc: 'Cardamom, saffron, pistachio' },
    ],
    items: [
      // momo
      { id: 'm1', cat: 'momo', name: 'Chicken Momo', price: 12, veg: false, spice: 1, popular: true, desc: 'Eight steamed dumplings, ginger-garlic chicken, sesame tomato achar', img: 'Chicken Momo' },
      { id: 'm2', cat: 'momo', name: 'Veg Momo', price: 11, veg: true, spice: 1, desc: 'Cabbage, paneer & herbs, steamed, timur-tomato chutney', img: 'Veg Momo' },
      { id: 'm3', cat: 'momo', name: 'Jhol Momo', price: 14, veg: false, spice: 2, popular: true, desc: 'Dumplings bathed in warm sesame-tomato jhol broth', img: 'Jhol Momo' },
      { id: 'm4', cat: 'momo', name: 'Fried Kothey Momo', price: 13, veg: false, spice: 1, desc: 'Pan-seared half-moon dumplings, crisp base', img: 'Kothey Momo' },
      // starters
      { id: 's1', cat: 'starters', name: 'Chicken Chilli', price: 13, veg: false, spice: 2, popular: true, desc: 'Wok-tossed, bell pepper, soy-garlic glaze', img: 'Chilli Chicken' },
      { id: 's2', cat: 'starters', name: 'Samosa Chaat', price: 10, veg: true, spice: 1, desc: 'Crushed samosa, chickpeas, yogurt, tamarind, pomegranate', img: 'Samosa Chaat' },
      { id: 's3', cat: 'starters', name: 'Sukuti Sadeko', price: 14, veg: false, spice: 3, desc: 'Himalayan dried meat, mustard oil, onion, timur', img: 'Sukuti Sadeko' },
      { id: 's4', cat: 'starters', name: 'Paneer Pakora', price: 10, veg: true, spice: 1, desc: 'Gram-flour battered cottage cheese, mint chutney', img: 'Paneer Pakora' },
      // tandoor
      { id: 't1', cat: 'tandoor', name: 'Tandoori Chicken', price: 18, veg: false, spice: 2, popular: true, desc: 'Half chicken, yogurt-spice marinade, charcoal-fired', img: 'Tandoori Chicken' },
      { id: 't2', cat: 'tandoor', name: 'Seekh Kebab', price: 17, veg: false, spice: 2, desc: 'Minced lamb skewers, garam masala, mint', img: 'Seekh Kebab' },
      { id: 't3', cat: 'tandoor', name: 'Malai Tikka', price: 17, veg: false, spice: 1, desc: 'Creamy cashew-marinated chicken, cardamom', img: 'Malai Tikka' },
      { id: 't4', cat: 'tandoor', name: 'Tandoori Broccoli', price: 14, veg: true, spice: 1, desc: 'Yogurt-spiced florets, smoked, mint raita', img: 'Tandoori Broccoli' },
      // curry
      { id: 'c1', cat: 'curry', name: 'Butter Chicken', price: 19, veg: false, spice: 1, popular: true, desc: 'Tomato-fenugreek gravy, cream, charred chicken tikka', img: 'Butter Chicken' },
      { id: 'c2', cat: 'curry', name: 'Goat Curry', price: 21, veg: false, spice: 3, desc: 'Bone-in goat, onion-tomato masala, slow braised', img: 'Goat Curry' },
      { id: 'c3', cat: 'curry', name: 'Paneer Tikka Masala', price: 17, veg: true, spice: 2, desc: 'Grilled paneer, creamy spiced tomato gravy', img: 'Paneer Masala' },
      { id: 'c4', cat: 'curry', name: 'Dal Makhani', price: 14, veg: true, spice: 1, popular: true, desc: 'Black lentils, simmered overnight, butter & cream', img: 'Dal Makhani' },
      { id: 'c5', cat: 'curry', name: 'Chana Masala', price: 13, veg: true, spice: 2, desc: 'Chickpeas, ginger, tomato, dried mango', img: 'Chana Masala' },
      // nepali
      { id: 'n1', cat: 'nepali', name: 'Nepali Thali', price: 24, veg: false, spice: 2, popular: true, desc: 'Dal, bhat, seasonal tarkari, gundruk, meat curry, achar', img: 'Nepali Thali' },
      { id: 'n2', cat: 'nepali', name: 'Veg Thali', price: 20, veg: true, spice: 1, desc: 'Dal, rice, three tarkari, gundruk, papad, achar', img: 'Veg Thali' },
      { id: 'n3', cat: 'nepali', name: 'Thukpa', price: 15, veg: false, spice: 2, desc: 'Himalayan noodle soup, vegetables, chicken', img: 'Thukpa' },
      { id: 'n4', cat: 'nepali', name: 'Chow Mein', price: 14, veg: false, spice: 1, desc: 'Wok-tossed noodles, vegetables, soy', img: 'Chow Mein' },
      // biryani
      { id: 'b1', cat: 'biryani', name: 'Hyderabadi Dum Biryani', price: 18, veg: false, spice: 2, popular: true, desc: 'Saffron basmati, chicken, fried onion, mint, raita', img: 'Chicken Biryani' },
      { id: 'b2', cat: 'biryani', name: 'Lamb Biryani', price: 21, veg: false, spice: 2, desc: 'Aged basmati layered with spiced lamb', img: 'Lamb Biryani' },
      { id: 'b3', cat: 'biryani', name: 'Vegetable Biryani', price: 15, veg: true, spice: 1, desc: 'Seasonal vegetables, whole spice, saffron', img: 'Veg Biryani' },
      // breads
      { id: 'br1', cat: 'breads', name: 'Garlic Naan', price: 5, veg: true, spice: 0, desc: 'Charred, brushed with garlic butter & cilantro', img: 'Garlic Naan' },
      { id: 'br2', cat: 'breads', name: 'Butter Naan', price: 4, veg: true, spice: 0, desc: 'Soft, blistered, clarified butter', img: 'Butter Naan' },
      { id: 'br3', cat: 'breads', name: 'Cheese Kulcha', price: 6, veg: true, spice: 0, desc: 'Stuffed with melted cheese & herbs', img: 'Cheese Kulcha' },
      // dessert
      { id: 'd1', cat: 'dessert', name: 'Gulab Jamun', price: 7, veg: true, spice: 0, popular: true, desc: 'Warm milk dumplings, cardamom-saffron syrup', img: 'Gulab Jamun' },
      { id: 'd2', cat: 'dessert', name: 'Kheer', price: 7, veg: true, spice: 0, desc: 'Slow-cooked rice pudding, pistachio, rose', img: 'Kheer' },
      { id: 'd3', cat: 'dessert', name: 'Mango Kulfi', price: 6, veg: true, spice: 0, desc: 'Dense Alphonso mango ice cream', img: 'Mango Kulfi' },
    ],
  };

  const promos = [
    { id: 'p1', badge: 'Weekday Lunch', title: 'Himalayan Lunch Thali', detail: 'A full thali with dal, bhat, two tarkari & meat curry — every weekday, 11:30–3.', price: '$14', accent: 'gold' },
    { id: 'p2', badge: 'Family Feast', title: '20% Off Orders Over $80', detail: 'Gather the table. Save on family-size curries, biryani & a dozen momo.', price: '−20%', accent: 'spice' },
    { id: 'p3', badge: 'New', title: 'Jhol Momo Tuesdays', detail: 'Our signature soup dumplings, two-for-one, every Tuesday evening.', price: '2-for-1', accent: 'gold' },
  ];

  const gallery = [
    'Tandoor flames', 'Momo plating', 'Thali spread', 'Dining room', 'Spice mise en place',
    'Chef at pass', 'Biryani unveiling', 'Naan from oven', 'Private table', 'Street-style chaat',
    'Masala grinding', 'Dessert trio',
  ];

  const reviews = [
    { name: 'Aarti S.', stars: 5, text: 'The jhol momo are unreal and the thali is the most authentic I\u2019ve had outside Kathmandu.', tag: 'Google' },
    { name: 'Marcus L.', stars: 5, text: 'Butter chicken with that smoky tikka — easily the best in the city. Warm, beautiful room.', tag: 'Yelp' },
    { name: 'Priya & Dev', stars: 5, text: 'Catered our 60-person event. Flawless, generous, and everyone asked who made the goat curry.', tag: 'Google' },
  ];

  // Categorized gallery: food, the restaurant spaces, events
  const galleryCats = [
    { id: 'food', name: 'The Food', items: ['Jhol momo, sesame broth', 'Butter chicken & tikka', 'Nepali thali spread', 'Biryani unveiling', 'Tandoori mixed grill', 'Dessert trio', 'Street-style chaat', 'Naan from the oven', 'Goat curry, bone-in'] },
    { id: 'space', name: 'The Restaurant', items: ['Main dining room', 'Candle-lit booths', 'The bar & lounge', 'Open tandoor station', 'Courtyard seating', 'Private dining room', 'Entrance & host stand', 'Chef\u2019s counter'] },
    { id: 'events', name: 'Events & Catering', items: ['Wedding mandap spread', 'Live momo station', 'Corporate lunch setup', 'Festival buffet', 'Family-style platters', 'Dessert table'] },
  ];

  // About-us content
  const about = {
    story: [
      'Indian Nepali Kitchen began with two cooks and one stubborn idea: that the food of the subcontinent and the food of the Himalayan hills belong at the same table.',
      'Our founders trained in the bustling kitchens of Delhi and the tea-house counters of Kathmandu. They grind their masalas every morning, pleat every momo by hand, and keep the charcoal tandoor burning from open to close.',
      'A decade later, the room is warmer, the menu is longer, and the welcome is exactly the same — pull up a chair, dinner\u2019s on the fire.',
    ],
    values: [
      { icon: 'flame', title: 'Cooked to order', text: 'Nothing sits under a heat lamp. Every plate is fired when you order it.' },
      { icon: 'leaf', title: 'Ground fresh daily', text: 'Whole spices, toasted and ground each morning — never from a jar.' },
      { icon: 'users', title: 'Family hospitality', text: 'We cook the way we\u2019d feed our own family. Generous, warm, unhurried.' },
    ],
    stats: [['10', 'years on Saffron Lane'], ['64', 'dishes, two cuisines'], ['1,200+', 'five-star reviews'], ['40k', 'momo pleated a year']],
    team: [
      { name: 'Suman Adhikari', role: 'Founder & Host', tag: 'Founder' },
      { name: 'Rekha Lama', role: 'Head Chef, Himalayan kitchen', tag: 'Chef' },
      { name: 'Arjun Mehta', role: 'Head Chef, Tandoor', tag: 'Chef' },
      { name: 'Bishnu Karki', role: 'General Manager', tag: 'Manager' },
    ],
  };

  const giftDesigns = [
    { id: 'gold', name: 'Saffron Gold', sub: 'Classic & elegant', accent: 'gold' },
    { id: 'spice', name: 'Spice Market', sub: 'Warm & festive', accent: 'spice' },
    { id: 'leaf', name: 'Himalaya', sub: 'Mountain motif', accent: 'leaf' },
  ];
  const giftAmounts = [25, 50, 75, 100, 150, 250];

  window.DATA = { menu, promos, gallery, galleryCats, reviews, about, giftDesigns, giftAmounts };
})();

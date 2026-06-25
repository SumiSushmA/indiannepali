<?php

namespace App\Data;

class SeedData
{
    public static function menu(): array
    {
        return [
            'categories' => [
                ['id' => 'momo', 'name' => 'Momo', 'tag' => 'Nepali Dumplings', 'desc' => 'Hand-pleated, steamed or fried, served with house achar'],
                ['id' => 'starters', 'name' => 'Starters', 'tag' => 'To Begin', 'desc' => 'Tandoor-charred and griddle-crisped small plates'],
                ['id' => 'tandoor', 'name' => 'From the Tandoor', 'tag' => 'Clay Oven', 'desc' => 'Marinated overnight, fired in our charcoal tandoor'],
                ['id' => 'curry', 'name' => 'Curries', 'tag' => 'House Classics', 'desc' => 'Slow-simmered gravies, ground fresh each morning'],
                ['id' => 'nepali', 'name' => 'Nepali Specials', 'tag' => 'From the Hills', 'desc' => 'Recipes carried from Kathmandu kitchens'],
                ['id' => 'biryani', 'name' => 'Biryani & Rice', 'tag' => 'Dum-Cooked', 'desc' => 'Sealed and steamed with aged basmati'],
                ['id' => 'breads', 'name' => 'Breads', 'tag' => 'Tandoor Baked', 'desc' => 'Pulled to order from the clay oven'],
                ['id' => 'dessert', 'name' => 'Sweets', 'tag' => 'To Finish', 'desc' => 'Cardamom, saffron, pistachio'],
            ],
            'items' => [
                ['id' => 'm1', 'cat' => 'momo', 'name' => 'Chicken Momo', 'price' => 12, 'veg' => false, 'spice' => 1, 'popular' => true, 'desc' => 'Eight steamed dumplings, ginger-garlic chicken, sesame tomato achar', 'img' => 'Chicken Momo'],
                ['id' => 'm2', 'cat' => 'momo', 'name' => 'Veg Momo', 'price' => 11, 'veg' => true, 'spice' => 1, 'desc' => 'Cabbage, paneer & herbs, steamed, timur-tomato chutney', 'img' => 'Veg Momo'],
                ['id' => 'm3', 'cat' => 'momo', 'name' => 'Jhol Momo', 'price' => 14, 'veg' => false, 'spice' => 2, 'popular' => true, 'desc' => 'Dumplings bathed in warm sesame-tomato jhol broth', 'img' => 'Jhol Momo'],
                ['id' => 'm4', 'cat' => 'momo', 'name' => 'Fried Kothey Momo', 'price' => 13, 'veg' => false, 'spice' => 1, 'desc' => 'Pan-seared half-moon dumplings, crisp base', 'img' => 'Kothey Momo'],
                ['id' => 's1', 'cat' => 'starters', 'name' => 'Chicken Chilli', 'price' => 13, 'veg' => false, 'spice' => 2, 'popular' => true, 'desc' => 'Wok-tossed, bell pepper, soy-garlic glaze', 'img' => 'Chilli Chicken'],
                ['id' => 's2', 'cat' => 'starters', 'name' => 'Samosa Chaat', 'price' => 10, 'veg' => true, 'spice' => 1, 'desc' => 'Crushed samosa, chickpeas, yogurt, tamarind, pomegranate', 'img' => 'Samosa Chaat'],
                ['id' => 's3', 'cat' => 'starters', 'name' => 'Sukuti Sadeko', 'price' => 14, 'veg' => false, 'spice' => 3, 'desc' => 'Himalayan dried meat, mustard oil, onion, timur', 'img' => 'Sukuti Sadeko'],
                ['id' => 's4', 'cat' => 'starters', 'name' => 'Paneer Pakora', 'price' => 10, 'veg' => true, 'spice' => 1, 'desc' => 'Gram-flour battered cottage cheese, mint chutney', 'img' => 'Paneer Pakora'],
                ['id' => 't1', 'cat' => 'tandoor', 'name' => 'Tandoori Chicken', 'price' => 18, 'veg' => false, 'spice' => 2, 'popular' => true, 'desc' => 'Half chicken, yogurt-spice marinade, charcoal-fired', 'img' => 'Tandoori Chicken'],
                ['id' => 't2', 'cat' => 'tandoor', 'name' => 'Seekh Kebab', 'price' => 17, 'veg' => false, 'spice' => 2, 'desc' => 'Minced lamb skewers, garam masala, mint', 'img' => 'Seekh Kebab'],
                ['id' => 't3', 'cat' => 'tandoor', 'name' => 'Malai Tikka', 'price' => 17, 'veg' => false, 'spice' => 1, 'desc' => 'Creamy cashew-marinated chicken, cardamom', 'img' => 'Malai Tikka'],
                ['id' => 't4', 'cat' => 'tandoor', 'name' => 'Tandoori Broccoli', 'price' => 14, 'veg' => true, 'spice' => 1, 'desc' => 'Yogurt-spiced florets, smoked, mint raita', 'img' => 'Tandoori Broccoli'],
                ['id' => 'c1', 'cat' => 'curry', 'name' => 'Butter Chicken', 'price' => 19, 'veg' => false, 'spice' => 1, 'popular' => true, 'desc' => 'Tomato-fenugreek gravy, cream, charred chicken tikka', 'img' => 'Butter Chicken'],
                ['id' => 'c2', 'cat' => 'curry', 'name' => 'Goat Curry', 'price' => 21, 'veg' => false, 'spice' => 3, 'desc' => 'Bone-in goat, onion-tomato masala, slow braised', 'img' => 'Goat Curry'],
                ['id' => 'c3', 'cat' => 'curry', 'name' => 'Paneer Tikka Masala', 'price' => 17, 'veg' => true, 'spice' => 2, 'desc' => 'Grilled paneer, creamy spiced tomato gravy', 'img' => 'Paneer Masala'],
                ['id' => 'c4', 'cat' => 'curry', 'name' => 'Dal Makhani', 'price' => 14, 'veg' => true, 'spice' => 1, 'popular' => true, 'desc' => 'Black lentils, simmered overnight, butter & cream', 'img' => 'Dal Makhani'],
                ['id' => 'c5', 'cat' => 'curry', 'name' => 'Chana Masala', 'price' => 13, 'veg' => true, 'spice' => 2, 'desc' => 'Chickpeas, ginger, tomato, dried mango', 'img' => 'Chana Masala'],
                ['id' => 'n1', 'cat' => 'nepali', 'name' => 'Nepali Thali', 'price' => 24, 'veg' => false, 'spice' => 2, 'popular' => true, 'desc' => 'Dal, bhat, seasonal tarkari, gundruk, meat curry, achar', 'img' => 'Nepali Thali'],
                ['id' => 'n2', 'cat' => 'nepali', 'name' => 'Veg Thali', 'price' => 20, 'veg' => true, 'spice' => 1, 'desc' => 'Dal, rice, three tarkari, gundruk, papad, achar', 'img' => 'Veg Thali'],
                ['id' => 'n3', 'cat' => 'nepali', 'name' => 'Thukpa', 'price' => 15, 'veg' => false, 'spice' => 2, 'desc' => 'Himalayan noodle soup, vegetables, chicken', 'img' => 'Thukpa'],
                ['id' => 'n4', 'cat' => 'nepali', 'name' => 'Chow Mein', 'price' => 14, 'veg' => false, 'spice' => 1, 'desc' => 'Wok-tossed noodles, vegetables, soy', 'img' => 'Chow Mein'],
                ['id' => 'b1', 'cat' => 'biryani', 'name' => 'Hyderabadi Dum Biryani', 'price' => 18, 'veg' => false, 'spice' => 2, 'popular' => true, 'desc' => 'Saffron basmati, chicken, fried onion, mint, raita', 'img' => 'Chicken Biryani'],
                ['id' => 'b2', 'cat' => 'biryani', 'name' => 'Lamb Biryani', 'price' => 21, 'veg' => false, 'spice' => 2, 'desc' => 'Aged basmati layered with spiced lamb', 'img' => 'Lamb Biryani'],
                ['id' => 'b3', 'cat' => 'biryani', 'name' => 'Vegetable Biryani', 'price' => 15, 'veg' => true, 'spice' => 1, 'desc' => 'Seasonal vegetables, whole spice, saffron', 'img' => 'Veg Biryani'],
                ['id' => 'br1', 'cat' => 'breads', 'name' => 'Garlic Naan', 'price' => 5, 'veg' => true, 'spice' => 0, 'desc' => 'Charred, brushed with garlic butter & cilantro', 'img' => 'Garlic Naan'],
                ['id' => 'br2', 'cat' => 'breads', 'name' => 'Butter Naan', 'price' => 4, 'veg' => true, 'spice' => 0, 'desc' => 'Soft, blistered, clarified butter', 'img' => 'Butter Naan'],
                ['id' => 'br3', 'cat' => 'breads', 'name' => 'Cheese Kulcha', 'price' => 6, 'veg' => true, 'spice' => 0, 'desc' => 'Stuffed with melted cheese & herbs', 'img' => 'Cheese Kulcha'],
                ['id' => 'd1', 'cat' => 'dessert', 'name' => 'Gulab Jamun', 'price' => 7, 'veg' => true, 'spice' => 0, 'popular' => true, 'desc' => 'Warm milk dumplings, cardamom-saffron syrup', 'img' => 'Gulab Jamun'],
                ['id' => 'd2', 'cat' => 'dessert', 'name' => 'Kheer', 'price' => 7, 'veg' => true, 'spice' => 0, 'desc' => 'Slow-cooked rice pudding, pistachio, rose', 'img' => 'Kheer'],
                ['id' => 'd3', 'cat' => 'dessert', 'name' => 'Mango Kulfi', 'price' => 6, 'veg' => true, 'spice' => 0, 'desc' => 'Dense Alphonso mango ice cream', 'img' => 'Mango Kulfi'],
            ],
        ];
    }

    public static function promos(): array
    {
        return [];
    }

    public static function reviews(): array
    {
        return [];
    }

    public static function galleryCategories(): array
    {
        return [
            ['id' => 'food', 'name' => 'The Food', 'items' => ['Jhol momo, sesame broth', 'Butter chicken & tikka', 'Nepali thali spread', 'Biryani unveiling', 'Tandoori mixed grill', 'Dessert trio', 'Street-style chaat', 'Naan from the oven', 'Goat curry, bone-in']],
            ['id' => 'space', 'name' => 'The Restaurant', 'items' => ['Main dining room', 'Candle-lit booths', 'The bar & lounge', 'Open tandoor station', 'Courtyard seating', 'Private dining room', 'Entrance & host stand', 'Chef\'s counter']],
            ['id' => 'events', 'name' => 'Events & Catering', 'items' => ['Wedding mandap spread', 'Live momo station', 'Corporate lunch setup', 'Festival buffet', 'Family-style platters', 'Dessert table']],
        ];
    }

    public static function about(): array
    {
        return [
            'story' => [
                'Indian Nepali Kitchen began with two cooks and one stubborn idea: that the food of the subcontinent and the food of the Himalayan hills belong at the same table.',
                'Our founders trained in the bustling kitchens of Delhi and the tea-house counters of Kathmandu. They grind their masalas every morning, pleat every momo by hand, and keep the charcoal tandoor burning from open to close.',
                'A decade later, the room is warmer, the menu is longer, and the welcome is exactly the same — pull up a chair, dinner\'s on the fire.',
            ],
            'values' => [
                ['icon' => 'flame', 'title' => 'Cooked to order', 'text' => 'Nothing sits under a heat lamp. Every plate is fired when you order it.'],
                ['icon' => 'leaf', 'title' => 'Ground fresh daily', 'text' => 'Whole spices, toasted and ground each morning — never from a jar.'],
                ['icon' => 'users', 'title' => 'Family hospitality', 'text' => 'We cook the way we\'d feed our own family. Generous, warm, unhurried.'],
            ],
            'stats' => [['10', 'years on Saffron Lane'], ['64', 'dishes, two cuisines'], ['1,200+', 'five-star reviews'], ['40k', 'momo pleated a year']],
            'team' => [
                ['name' => 'Suman Adhikari', 'role' => 'Founder & Host', 'tag' => 'Founder'],
                ['name' => 'Rekha Lama', 'role' => 'Head Chef, Himalayan kitchen', 'tag' => 'Chef'],
                ['name' => 'Arjun Mehta', 'role' => 'Head Chef, Tandoor', 'tag' => 'Chef'],
                ['name' => 'Bishnu Karki', 'role' => 'General Manager', 'tag' => 'Manager'],
            ],
        ];
    }

    public static function giftDesigns(): array
    {
        return [
            ['id' => 'gold', 'name' => 'Saffron Gold', 'sub' => 'Classic & elegant', 'accent' => 'gold'],
            ['id' => 'spice', 'name' => 'Spice Market', 'sub' => 'Warm & festive', 'accent' => 'spice'],
            ['id' => 'leaf', 'name' => 'Himalaya', 'sub' => 'Mountain motif', 'accent' => 'leaf'],
        ];
    }

    public static function contentBlocks(): array
    {
        return [
            ['section' => 'Hero headline', 'value' => 'Where the Himalayas meet the tandoor.', 'type' => 'Text'],
            ['section' => 'Hero subtext', 'value' => 'Seattle\'s go-to for momo, tandoori & curries — Nepali thali served daily on Aurora Avenue.', 'type' => 'Text'],
            ['section' => 'Footer tagline', 'value' => 'Hand-pleated momo, charcoal tandoor, and the slow-simmered curries of the Indian subcontinent and the Himalayan hills.', 'type' => 'Text'],
            ['section' => 'Hours banner', 'value' => 'Tue–Sun · 11:30 – 22:00', 'type' => 'Text'],
            ['section' => 'Promo 1', 'value' => 'Himalayan Lunch Thali — $14', 'type' => 'Promotion'],
            ['section' => 'Promo 2', 'value' => '20% off orders over $80', 'type' => 'Promotion'],
            ['section' => 'Gallery', 'value' => '12 images', 'type' => 'Media'],
            ['section' => 'About story', 'value' => 'Two kitchens, one table…', 'type' => 'Text'],
        ];
    }

    public static function users(): array
    {
        return [
            ['name' => 'Baburam', 'role' => 'Owner', 'email' => 'baburam@indiannepalikitchen.kitchen', 'status' => 'Active', 'last' => '2m ago'],
            ['name' => 'Bishnu Karki', 'role' => 'Manager', 'email' => 'bishnu@indiannepali.kitchen', 'status' => 'Active', 'last' => '1h ago'],
            ['name' => 'Rekha Lama', 'role' => 'Chef', 'email' => 'rekha@indiannepali.kitchen', 'status' => 'Active', 'last' => 'Today'],
            ['name' => 'Daniel Ortiz', 'role' => 'Front of house', 'email' => 'daniel@indiannepali.kitchen', 'status' => 'Active', 'last' => 'Yesterday'],
            ['name' => 'Anita Shrestha', 'role' => 'Marketing', 'email' => 'anita@indiannepali.kitchen', 'status' => 'Invited', 'last' => '—'],
            ['name' => 'Kevin Wu', 'role' => 'Front of house', 'email' => 'kevin@indiannepali.kitchen', 'status' => 'Inactive', 'last' => '3 wks ago'],
        ];
    }

    public static function orders(): array
    {
        return [
            ['id' => 'NK-4850', 'customer' => 'Asha Gurung', 'type' => 'Delivery', 'status' => 'New', 'items' => [['name' => 'Chicken Momo', 'qty' => 2], ['name' => 'Butter Chicken', 'qty' => 1]], 'total' => 52, 'channel' => 'Website', 'mins' => 12],
            ['id' => 'NK-4849', 'customer' => 'Marcus Lee', 'type' => 'Pickup', 'status' => 'New', 'items' => [['name' => 'Jhol Momo', 'qty' => 1], ['name' => 'Garlic Naan', 'qty' => 2]], 'total' => 34, 'channel' => 'Toast POS', 'mins' => 28],
            ['id' => 'NK-4848', 'customer' => 'Priya Sharma', 'type' => 'Delivery', 'status' => 'New', 'items' => [['name' => 'Nepali Thali', 'qty' => 2]], 'total' => 58, 'channel' => 'Phone', 'mins' => 35],
            ['id' => 'NK-4847', 'customer' => 'Dawa Sherpa', 'type' => 'Pickup', 'status' => 'Preparing', 'items' => [['name' => 'Hyderabadi Dum Biryani', 'qty' => 1], ['name' => 'Gulab Jamun', 'qty' => 1]], 'total' => 32, 'channel' => 'Website', 'mins' => 48],
            ['id' => 'NK-4846', 'customer' => 'Elena Ruiz', 'type' => 'Delivery', 'status' => 'Preparing', 'items' => [['name' => 'Tandoori Chicken', 'qty' => 1], ['name' => 'Dal Makhani', 'qty' => 1], ['name' => 'Butter Naan', 'qty' => 2]], 'total' => 44, 'channel' => 'Website', 'mins' => 62],
            ['id' => 'NK-4845', 'customer' => 'Tom Becker', 'type' => 'Pickup', 'status' => 'Preparing', 'items' => [['name' => 'Goat Curry', 'qty' => 1], ['name' => 'Garlic Naan', 'qty' => 1]], 'total' => 31, 'channel' => 'Toast POS', 'mins' => 75],
            ['id' => 'NK-4844', 'customer' => 'Nisha Rai', 'type' => 'Delivery', 'status' => 'Ready', 'items' => [['name' => 'Chicken Chilli', 'qty' => 1], ['name' => 'Thukpa', 'qty' => 1]], 'total' => 35, 'channel' => 'Phone', 'mins' => 95],
            ['id' => 'NK-4843', 'customer' => 'Omar Haddad', 'type' => 'Pickup', 'status' => 'Ready', 'items' => [['name' => 'Chicken Momo', 'qty' => 1], ['name' => 'Samosa Chaat', 'qty' => 1]], 'total' => 27, 'channel' => 'Website', 'mins' => 110],
            ['id' => 'NK-4842', 'customer' => 'Grace Kim', 'type' => 'Delivery', 'status' => 'Out for delivery', 'items' => [['name' => 'Butter Chicken', 'qty' => 2], ['name' => 'Garlic Naan', 'qty' => 2]], 'total' => 48, 'channel' => 'Website', 'mins' => 140],
            ['id' => 'NK-4841', 'customer' => 'Bikash Thapa', 'type' => 'Delivery', 'status' => 'Out for delivery', 'items' => [['name' => 'Jhol Momo', 'qty' => 2], ['name' => 'Mango Kulfi', 'qty' => 2]], 'total' => 40, 'channel' => 'Toast POS', 'mins' => 165],
        ];
    }

    public static function reservations(): array
    {
        return [
            ['id' => 'R-2100', 'name' => 'Sara Cohen', 'party' => 4, 'date' => '2026-06-05', 'time' => '19:00', 'status' => 'Confirmed', 'table' => 'T6', 'occasion' => 'Birthday', 'phone' => '(415) 555-0100'],
            ['id' => 'R-2101', 'name' => 'Liam Walsh', 'party' => 2, 'date' => '2026-06-05', 'time' => '18:30', 'status' => 'Seated', 'table' => 'T3', 'occasion' => 'Date night', 'phone' => '(415) 555-0101'],
            ['id' => 'R-2102', 'name' => 'Maya Patel', 'party' => 6, 'date' => '2026-06-06', 'time' => '20:00', 'status' => 'Pending', 'table' => 'T12', 'occasion' => '—', 'phone' => '(415) 555-0102'],
            ['id' => 'R-2103', 'name' => 'Jon Park', 'party' => 3, 'date' => '2026-06-07', 'time' => '19:30', 'status' => 'Confirmed', 'table' => 'T8', 'occasion' => 'Anniversary', 'phone' => '(415) 555-0103'],
            ['id' => 'R-2104', 'name' => 'Rita Magar', 'party' => 8, 'date' => '2026-06-08', 'time' => '18:00', 'status' => 'Confirmed', 'table' => 'T15', 'occasion' => 'Business', 'phone' => '(415) 555-0104'],
            ['id' => 'R-2105', 'name' => 'Asha Gurung', 'party' => 2, 'date' => '2026-06-09', 'time' => '17:30', 'status' => 'Pending', 'table' => 'T2', 'occasion' => '—', 'phone' => '(415) 555-0105'],
            ['id' => 'R-2106', 'name' => 'Marcus Lee', 'party' => 5, 'date' => '2026-06-10', 'time' => '20:30', 'status' => 'Cancelled', 'table' => 'T10', 'occasion' => '—', 'phone' => '(415) 555-0106'],
            ['id' => 'R-2107', 'name' => 'Priya Sharma', 'party' => 4, 'date' => '2026-06-11', 'time' => '19:00', 'status' => 'Completed', 'table' => 'T7', 'occasion' => 'Birthday', 'phone' => '(415) 555-0107'],
            ['id' => 'R-2108', 'name' => 'Dawa Sherpa', 'party' => 2, 'date' => '2026-06-12', 'time' => '18:30', 'status' => 'Confirmed', 'table' => 'T4', 'occasion' => 'Date night', 'phone' => '(415) 555-0108'],
            ['id' => 'R-2109', 'name' => 'Elena Ruiz', 'party' => 7, 'date' => '2026-06-13', 'time' => '19:30', 'status' => 'Pending', 'table' => 'T14', 'occasion' => '—', 'phone' => '(415) 555-0109'],
        ];
    }

    public static function cateringInquiries(): array
    {
        return [
            ['id' => 'C-510', 'name' => 'Tom Becker', 'event' => 'Wedding', 'guests' => 120, 'date' => '2026-07-12', 'status' => 'New', 'value' => 4200, 'days' => 1],
            ['id' => 'C-511', 'name' => 'Nisha Rai', 'event' => 'Corporate', 'guests' => 60, 'date' => '2026-06-20', 'status' => 'New', 'value' => 2800, 'days' => 2],
            ['id' => 'C-512', 'name' => 'Omar Haddad', 'event' => 'Birthday', 'guests' => 45, 'date' => '2026-06-28', 'status' => 'Quoted', 'value' => 1650, 'days' => 3],
            ['id' => 'C-513', 'name' => 'Grace Kim', 'event' => 'Puja / religious', 'guests' => 80, 'date' => '2026-07-05', 'status' => 'Quoted', 'value' => 3100, 'days' => 4],
            ['id' => 'C-514', 'name' => 'Bikash Thapa', 'event' => 'Corporate', 'guests' => 150, 'date' => '2026-08-15', 'status' => 'In conversation', 'value' => 5800, 'days' => 2],
            ['id' => 'C-515', 'name' => 'Sara Cohen', 'event' => 'Wedding', 'guests' => 200, 'date' => '2026-09-20', 'status' => 'In conversation', 'value' => 9200, 'days' => 5],
            ['id' => 'C-516', 'name' => 'Liam Walsh', 'event' => 'Other', 'guests' => 30, 'date' => '2026-06-18', 'status' => 'Booked', 'value' => 1100, 'days' => 1],
            ['id' => 'C-517', 'name' => 'Maya Patel', 'event' => 'Birthday', 'guests' => 40, 'date' => '2026-07-02', 'status' => 'Booked', 'value' => 1400, 'days' => 3],
        ];
    }

    public static function contactMessages(): array
    {
        return [
            ['id' => 'M-880', 'name' => 'Jon Park', 'email' => 'jon.park@email.com', 'subject' => 'Lost item', 'status' => 'Unread', 'days' => 0, 'preview' => 'Hi, I left a scarf at table 6 last night…'],
            ['id' => 'M-881', 'name' => 'Rita Magar', 'email' => 'rita.magar@email.com', 'subject' => 'Allergy question', 'status' => 'Unread', 'days' => 1, 'preview' => 'Do your momo contain gluten? My son…'],
            ['id' => 'M-882', 'name' => 'Asha Gurung', 'email' => 'asha.gurung@email.com', 'subject' => 'Feedback', 'status' => 'Unread', 'days' => 2, 'preview' => 'Wonderful dinner — please thank the chef!'],
            ['id' => 'M-883', 'name' => 'Marcus Lee', 'email' => 'marcus.lee@email.com', 'subject' => 'Gift cards', 'status' => 'Unread', 'days' => 3, 'preview' => 'Looking to buy a $100 gift card for…'],
            ['id' => 'M-884', 'name' => 'Priya Sharma', 'email' => 'priya.sharma@email.com', 'subject' => 'Private dining', 'status' => 'Open', 'days' => 4, 'preview' => 'Interested in the chef\'s counter for 8…'],
            ['id' => 'M-885', 'name' => 'Dawa Sherpa', 'email' => 'dawa.sherpa@email.com', 'subject' => 'Job application', 'status' => 'Open', 'days' => 5, 'preview' => 'I have five years of tandoor experience…'],
            ['id' => 'M-886', 'name' => 'Elena Ruiz', 'email' => 'elena.ruiz@email.com', 'subject' => 'Press inquiry', 'status' => 'Resolved', 'days' => 6, 'preview' => 'Writing a feature on Himalayan cuisine…'],
            ['id' => 'M-887', 'name' => 'Tom Becker', 'email' => 'tom.becker@email.com', 'subject' => 'Feedback', 'status' => 'Resolved', 'days' => 7, 'preview' => 'The jhol momo were outstanding last Friday.'],
        ];
    }

    public static function giftCards(): array
    {
        return [
            ['code' => 'NK-SAFF-1000', 'design' => 'Saffron Gold', 'face' => 100, 'balance' => 100, 'status' => 'Active', 'recipient' => 'Asha Gurung', 'channel' => 'Online', 'issued' => '2026-04-12'],
            ['code' => 'NK-SPCE-1001', 'design' => 'Spice Market', 'face' => 50, 'balance' => 50, 'status' => 'Active', 'recipient' => 'Marcus Lee', 'channel' => 'In-store', 'issued' => '2026-04-18'],
            ['code' => 'NK-HMAL-1002', 'design' => 'Himalaya', 'face' => 75, 'balance' => 75, 'status' => 'Active', 'recipient' => 'Priya Sharma', 'channel' => 'Online', 'issued' => '2026-05-02'],
            ['code' => 'NK-GIFT-1003', 'design' => 'Saffron Gold', 'face' => 150, 'balance' => 85, 'status' => 'Partially used', 'recipient' => 'Dawa Sherpa', 'channel' => 'Online', 'issued' => '2026-05-10'],
            ['code' => 'NK-FEST-1004', 'design' => 'Spice Market', 'face' => 250, 'balance' => 120, 'status' => 'Partially used', 'recipient' => 'Elena Ruiz', 'channel' => 'In-store', 'issued' => '2026-05-15'],
            ['code' => 'NK-WARM-1005', 'design' => 'Himalaya', 'face' => 25, 'balance' => 0, 'status' => 'Redeemed', 'recipient' => 'Tom Becker', 'channel' => 'Online', 'issued' => '2026-04-25'],
            ['code' => 'NK-RLUX-1006', 'design' => 'Saffron Gold', 'face' => 100, 'balance' => 0, 'status' => 'Redeemed', 'recipient' => 'Nisha Rai', 'channel' => 'In-store', 'issued' => '2026-05-01'],
            ['code' => 'NK-THAL-1007', 'design' => 'Spice Market', 'face' => 50, 'balance' => 30, 'status' => 'Partially used', 'recipient' => 'Omar Haddad', 'channel' => 'Online', 'issued' => '2026-05-20'],
        ];
    }

    public static function toastLogs(): array
    {
        return [
            ['t' => '14:32', 'm' => 'Order NK-4850 pushed to POS · ticket #A192', 'ok' => true],
            ['t' => '14:31', 'm' => 'Menu sync complete · 64 items, 0 conflicts', 'ok' => true],
            ['t' => '14:20', 'm' => '"Goat Curry" marked 86\'d from POS — hidden on web', 'ok' => true],
            ['t' => '13:58', 'm' => 'Payout reconciliation pending — awaiting batch close', 'ok' => false],
            ['t' => '13:40', 'm' => 'Order NK-4848 fulfilled · synced to POS', 'ok' => true],
        ];
    }
}

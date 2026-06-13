<?php

namespace App\Data;

/**
 * Content aligned with indiannepalikitchen.com and public menu listings.
 * Menu structure and items sourced from the restaurant's online ordering catalog.
 */
class LiveSiteContent
{
    public static function settings(): array
    {
        return [
            'restaurant_name' => 'Indian-Nepali Kitchen',
            'address' => '13754 Aurora Ave N, Suite D',
            'city' => 'Seattle, WA 98133',
            'phone' => '(206) 397-3211',
            'email' => 'hello@indiannepalikitchen.com',
            'hours' => 'Daily · 10:00 AM – 9:30 PM',
            'closed_days' => '',
            'tax_rate' => '0.1025',
            'delivery_fee' => '6.98',
            'free_delivery_min' => '999',
            'footer_tagline' => 'Discover the fusion magic of India and Nepal — authentic Himalayan and Indian cuisine on Aurora Avenue, Seattle.',
            'online_ordering_enabled' => 'true',
            'delivery_enabled' => 'true',
            'tips_enabled' => 'true',
            'sms_alerts_enabled' => 'true',
            'toast_location' => '13754 Aurora Ave N · Seattle',
            'toast_connected' => 'true',
            'instagram_url' => 'https://www.instagram.com/indiannepalikitchen',
            'facebook_url' => 'https://www.facebook.com/indiannepalikitchen',
            'whatsapp_url' => 'https://wa.me/12063973211',
            'privacy_url' => 'https://www.indiannepalikitchen.com/',
            'terms_url' => 'https://www.indiannepalikitchen.com/',
            'accessibility_url' => 'https://www.indiannepalikitchen.com/',
        ];
    }

    public static function contentBlocks(): array
    {
        return [
            'Hero headline' => 'Discover the fusion magic of India and Nepal.',
            'Hero subtext' => 'Seattle\'s go-to for momo, tandoori, and comforting curries — from juicy chicken dumplings and butter lamb curry to garlic naan and Nepali thali, cooked with passion on Aurora Avenue.',
            'Footer tagline' => 'Discover the fusion magic of India and Nepal — momo, curries, tandoori, and Nepali specialties. Dine in, pickup, or delivery.',
            'Hours banner' => 'Daily · 10:00 AM – 9:30 PM',
            'About story' => 'Indian & Nepali cuisine on Aurora Avenue.',
            'Home story title' => 'Come for the momos, stay for everything else',
            'Home story text' => 'Indian-Nepali Kitchen serves the kind of meal worth traveling across Seattle for — with one of the city\'s best momo menus, moody black-and-red dining room, and a whole spectrum of Indian and Nepali comfort food.',
            'Delivery blurb' => 'Order online for pickup or delivery. Estimated 30–60 minutes. Delivery available within the local area.',
            'Catering blurb' => 'Planning a party, office lunch, or celebration? Catering is available for groups of 20 people or more.',
        ];
    }

    public static function about(): array
    {
        return [
            'story' => [
                'Indian-Nepali Kitchen is an Indian and Nepalese restaurant on Aurora Avenue in Seattle — a low-key spot serving meals worthy of bragging about, with momo at the forefront.',
                'The menu spans steamed, fried, jhol, tandoori, and chili momos; butter and tikka masala curries; Nepali specials like gundruk and aloo rayo ko saag; tandoori platters; biryanis; and breads baked to order.',
                'Whether you dine in, pick up, or order delivery, expect generous portions, authentic spice levels, and the warm hospitality of a family-run kitchen.',
            ],
            'values' => [
                ['icon' => 'flame', 'title' => 'Cooked to order', 'text' => 'Every plate is fired when you order it — from tandoori momos to brothy house curry.'],
                ['icon' => 'leaf', 'title' => 'Indian & Nepali roots', 'text' => 'Recipes from the subcontinent and the Himalayan hills, with vegetarian and vegan options throughout the menu.'],
                ['icon' => 'users', 'title' => 'Family hospitality', 'text' => 'Counter service, cozy dining room, and the kind of welcome that keeps neighbors coming back.'],
            ],
            'stats' => [
                ['4.87', 'average rating online'],
                ['14', 'menu categories'],
                ['10–9:30', 'open daily'],
                ['13754', 'Aurora Ave N, Seattle'],
            ],
            'team' => [
                ['name' => 'Our kitchen team', 'role' => 'Chefs & tandoor', 'tag' => 'Kitchen'],
                ['name' => 'Front of house', 'role' => 'Service & hospitality', 'tag' => 'Team'],
            ],
        ];
    }

    public static function reviews(): array
    {
        return [
            ['name' => 'The Infatuation', 'stars' => 5, 'text' => 'Come for the momos, stay for everything else — these juicy chicken parcels are among the best in Seattle, and the butter curry is silky-smooth.', 'tag' => 'Press'],
            ['name' => 'Corner', 'stars' => 5, 'text' => 'All three momo styles — steamed, fried, and jhol in tangy sesame broth. Himalayan comfort food with spice levels that actually mean something.', 'tag' => 'Review'],
            ['name' => 'Guest', 'stars' => 5, 'text' => 'Jhol momo, tandoori momo, garlic basil naan, and lamb butter curry — everything hits. Grateful this lovely place exists on Aurora.', 'tag' => 'Google'],
        ];
    }

    public static function promos(): array
    {
        return [
            ['id' => 'order-online', 'badge' => 'Order online', 'title' => 'Pickup & delivery', 'detail' => 'Browse the full menu — appetizers, momo, entrees, Nepali specials, tandoori, biryani, breads, and desserts — and order for pickup or delivery.', 'price' => 'Menu', 'accent' => 'gold'],
            ['id' => 'momo-combo', 'badge' => 'Fan favorite', 'title' => 'Combo Momo (12 pcs)', 'detail' => 'Steamed, fried, sandheko, and chili momo in one order — the easiest way to try the full momo lineup.', 'price' => '$14.99+', 'accent' => 'spice'],
            ['id' => 'catering', 'badge' => 'Catering', 'title' => 'Events & groups (20+ guests)', 'detail' => 'Family trays, live momo stations, and full spreads for weddings, office lunches, pujas, and celebrations.', 'price' => 'Quote', 'accent' => 'gold'],
        ];
    }

    public static function galleryCategories(): array
    {
        return [
            ['id' => 'food', 'name' => 'The Food', 'items' => [
                'Jhol (soup) Momo', 'Combo Momo', 'Tandoori Chicken Momos', 'Butter Masala Momo',
                'Chicken Chili', 'Vegetable Pakora', 'Samosa Chaat', 'Butter Curry with lamb',
                'House Curry', 'Aloo Gobi', 'Garlic Basil Naan', 'Biryani Lamb',
                'Gulab Jamun', 'Daal Soup',
            ]],
            ['id' => 'space', 'name' => 'The Restaurant', 'items' => [
                'Dining room on Aurora Ave', 'Counter service', 'Tandoor clay oven',
                'Cozy black and red interior', 'Family-friendly seating',
            ]],
            ['id' => 'events', 'name' => 'Catering & Events', 'items' => [
                'Catering spread', 'Live momo station', 'Family-size trays',
                'Office lunch setup', 'Celebration feast',
            ]],
        ];
    }

    public static function menu(): array
    {
        return [
            'categories' => [
                ['id' => 'appetizer', 'name' => 'Appetizer', 'tag' => 'Starters', 'desc' => 'Samosa, pakora, chili, and Nepali street snacks'],
                ['id' => 'momo', 'name' => 'Momo · Chow Mein · Thukpa', 'tag' => 'Nepali favorites', 'desc' => 'Handmade dumplings, noodles, and Himalayan soup'],
                ['id' => 'entrees', 'name' => 'Entrees', 'tag' => 'Curries', 'desc' => 'Served with rice — house, butter, tikka masala, and more'],
                ['id' => 'nepali-special', 'name' => 'Nepali Special Menu', 'tag' => 'From the hills', 'desc' => 'Gundruk, sekuwa, and traditional Nepali plates'],
                ['id' => 'vegetarian', 'name' => 'Vegetarian Specialties', 'tag' => 'Veg & vegan', 'desc' => 'Paneer, dal, saag, and vegetable curries — served with rice'],
                ['id' => 'tandoori', 'name' => 'Tandoori', 'tag' => 'Clay oven', 'desc' => 'Marinated and roasted in our tandoori oven — served with rice'],
                ['id' => 'salad', 'name' => 'Salad', 'tag' => 'Fresh', 'desc' => 'Green, Caesar, and coleslaw salads'],
                ['id' => 'soup', 'name' => 'Soup', 'tag' => 'Warm bowls', 'desc' => 'Daal, chicken, and mixed bean soups'],
                ['id' => 'breads', 'name' => 'Breads', 'tag' => 'Tandoor baked', 'desc' => 'Naan and Indian breads'],
                ['id' => 'desserts', 'name' => 'Desserts', 'tag' => 'Sweet finish', 'desc' => 'Classic Indian sweets and kulfi'],
                ['id' => 'sides', 'name' => 'Sides', 'tag' => 'Extras', 'desc' => 'Raita, chutneys, and sauces'],
                ['id' => 'biryani', 'name' => 'Biryani', 'tag' => 'Dum rice', 'desc' => 'Aromatic basmati biryani'],
                ['id' => 'rice', 'name' => 'Rice', 'tag' => 'Basmati', 'desc' => 'Fried rice and plain basmati'],
                ['id' => 'drinks', 'name' => 'Drinks', 'tag' => 'Beverages', 'desc' => 'Lassi, chai, juice, and soft drinks'],
            ],
            'items' => [
                // Appetizer
                ['id' => 'veg-samosa', 'cat' => 'appetizer', 'name' => 'Vegetable Samosa (2 pcs)', 'price' => 6.95, 'veg' => true, 'spice' => 1, 'popular' => true, 'desc' => 'Deep fried pastries filled with mildly spiced potatoes and peas.', 'img' => 'Vegetable Samosa'],
                ['id' => 'chicken-samosa', 'cat' => 'appetizer', 'name' => 'Chicken Samosa (2 pcs)', 'price' => 6.99, 'veg' => false, 'spice' => 1, 'desc' => 'Deep fried pastries filled with spiced chicken.', 'img' => 'Chicken Samosa'],
                ['id' => 'lamb-samosa', 'cat' => 'appetizer', 'name' => 'Lamb Samosa (2 pcs)', 'price' => 7.95, 'veg' => false, 'spice' => 2, 'desc' => 'Deep fried pastries stuffed with ground lamb and Indian spices.', 'img' => 'Lamb Samosa'],
                ['id' => 'samosa-chaat', 'cat' => 'appetizer', 'name' => 'Samosa Chaat', 'price' => 7.95, 'veg' => true, 'spice' => 1, 'popular' => true, 'desc' => 'Vegetable samosa with mint & tamarind sauce, yogurt and garbanzo beans.', 'img' => 'Samosa Chaat'],
                ['id' => 'veg-pakora', 'cat' => 'appetizer', 'name' => 'Vegetable Pakora (8 pcs)', 'price' => 6.50, 'veg' => true, 'spice' => 1, 'popular' => true, 'desc' => 'Mixed vegetables deep-fried in gram flour batter.', 'img' => 'Vegetable Pakora'],
                ['id' => 'paneer-pakora', 'cat' => 'appetizer', 'name' => 'Paneer Pakora (7 pcs)', 'price' => 7.50, 'veg' => true, 'spice' => 1, 'desc' => 'Homemade cheese deep-fried in gram flour batter.', 'img' => 'Paneer Pakora'],
                ['id' => 'chicken-pakora', 'cat' => 'appetizer', 'name' => 'Chicken Pakora (8 pcs)', 'price' => 9.99, 'veg' => false, 'spice' => 1, 'desc' => 'Diced chicken deep-fried in gram flour batter.', 'img' => 'Chicken Pakora'],
                ['id' => 'chicken-chili', 'cat' => 'appetizer', 'name' => 'Chicken Chili (8 pcs)', 'price' => 10.99, 'veg' => false, 'spice' => 2, 'popular' => true, 'desc' => 'Chicken sautéed with bell pepper, onion, and tomato sauce.', 'img' => 'Chicken Chili'],
                ['id' => 'gobi-manchurian', 'cat' => 'appetizer', 'name' => 'Gobi Manchurian', 'price' => 7.50, 'veg' => true, 'spice' => 2, 'desc' => 'Cauliflower seasoned with garlic and a blend of spices.', 'img' => 'Gobi Manchurian'],
                ['id' => 'chatpate', 'cat' => 'appetizer', 'name' => 'Chatpate', 'price' => 7.99, 'veg' => true, 'spice' => 2, 'desc' => 'Popular Nepali snack with puffed rice, sev, peanuts, and chopped vegetables.', 'img' => 'Chatpate'],
                ['id' => 'papadam', 'cat' => 'appetizer', 'name' => 'Papadam (2 pcs)', 'price' => 1.99, 'veg' => true, 'spice' => 0, 'desc' => 'Crispy gram flour wafers with mint and tamarind chutneys.', 'img' => 'Papadam'],
                // Momo
                ['id' => 'momo-10', 'cat' => 'momo', 'name' => 'Momo (10 pcs)', 'price' => 11.99, 'veg' => false, 'spice' => 1, 'popular' => true, 'desc' => 'Steamed dumplings with vegetables and ground chicken, ginger, garlic, cilantro, and spices.', 'img' => 'Chicken Momo'],
                ['id' => 'combo-momo', 'cat' => 'momo', 'name' => 'Combo Momo (12 pcs)', 'price' => 14.99, 'veg' => false, 'spice' => 2, 'popular' => true, 'desc' => 'Steamed, fried, sandheko, and chili momo — four styles in one order.', 'img' => 'Combo Momo'],
                ['id' => 'jhol-momo', 'cat' => 'momo', 'name' => 'Jhol (Soup) Momo', 'price' => 13.99, 'veg' => false, 'spice' => 2, 'popular' => true, 'desc' => 'Dumplings served in a flavorful Nepali soup or sauce.', 'img' => 'Jhol Momo'],
                ['id' => 'fried-momo', 'cat' => 'momo', 'name' => 'Fried Momo', 'price' => 13.99, 'veg' => false, 'spice' => 1, 'desc' => 'Momos deep fried until crisp.', 'img' => 'Fried Momo'],
                ['id' => 'sandheko-momo', 'cat' => 'momo', 'name' => 'Sandheko Momo', 'price' => 13.99, 'veg' => false, 'spice' => 2, 'desc' => 'Marinated with Nepali spices and Himalayan herbs.', 'img' => 'Sandheko Momo'],
                ['id' => 'butter-masala-momo', 'cat' => 'momo', 'name' => 'Butter Masala Momo', 'price' => 18.99, 'veg' => false, 'spice' => 2, 'desc' => 'Fried momo in tomato sauce with cream and butter.', 'img' => 'Butter Masala Momo'],
                ['id' => 'tandoori-momo', 'cat' => 'momo', 'name' => 'Tandoori Chicken Momos', 'price' => 19.99, 'veg' => false, 'spice' => 2, 'popular' => true, 'desc' => 'Yogurt-marinated chicken momos cooked in the tandoor oven.', 'img' => 'Tandoori Chicken Momos'],
                ['id' => 'chilli-momo', 'cat' => 'momo', 'name' => 'Chilli Momo', 'price' => 13.99, 'veg' => false, 'spice' => 3, 'desc' => 'Steamed momo with onion, chili, peppers and tomato sauce.', 'img' => 'Chilli Momo'],
                ['id' => 'kothey-momo', 'cat' => 'momo', 'name' => 'Kothey Momo (regular)', 'price' => 13.99, 'veg' => false, 'spice' => 1, 'desc' => 'Pan fried on the bottom for a crisp base.', 'img' => 'Kothey Momo'],
                ['id' => 'chow-mein', 'cat' => 'momo', 'name' => 'Chow Mein', 'price' => 13.50, 'veg' => false, 'spice' => 1, 'desc' => 'Stir fried noodles with onion, garlic, ginger and Nepali spices.', 'img' => 'Chow Mein'],
                ['id' => 'thukpa', 'cat' => 'momo', 'name' => 'Thukpa', 'price' => 12.99, 'veg' => false, 'spice' => 2, 'popular' => true, 'desc' => 'Traditional curry soup with vegetables, chickpeas and noodles.', 'img' => 'Thukpa'],
                // Entrees
                ['id' => 'house-curry', 'cat' => 'entrees', 'name' => 'House Curry', 'price' => 17.95, 'veg' => false, 'spice' => 2, 'popular' => true, 'desc' => 'Fresh onions, tomatoes, garlic and ginger in a special sauce. Served with rice.', 'img' => 'House Curry'],
                ['id' => 'butter-curry', 'cat' => 'entrees', 'name' => 'Butter Curry', 'price' => 17.95, 'veg' => false, 'spice' => 1, 'popular' => true, 'desc' => 'Tomato sauce with spices, cream and butter. Served with rice.', 'img' => 'Butter Curry'],
                ['id' => 'tikka-masala', 'cat' => 'entrees', 'name' => 'Tikka Masala', 'price' => 17.95, 'veg' => false, 'spice' => 2, 'desc' => 'Tomato and onion sauce with spices and cream. Served with rice.', 'img' => 'Tikka Masala'],
                ['id' => 'karahi', 'cat' => 'entrees', 'name' => 'Karahi', 'price' => 17.95, 'veg' => false, 'spice' => 2, 'desc' => 'Tomatoes, onion, ginger, garlic and fresh pepper. Served with rice.', 'img' => 'Karahi'],
                ['id' => 'mango-curry', 'cat' => 'entrees', 'name' => 'Mango Curry', 'price' => 17.95, 'veg' => false, 'spice' => 1, 'desc' => 'Onion sauce with cream and mango. Served with rice.', 'img' => 'Mango Curry'],
                ['id' => 'korma', 'cat' => 'entrees', 'name' => 'Korma', 'price' => 17.95, 'veg' => false, 'spice' => 1, 'desc' => 'Creamy sauce with cashews and almonds. Served with rice.', 'img' => 'Korma'],
                ['id' => 'vindaloo', 'cat' => 'entrees', 'name' => 'Vindaloo', 'price' => 17.95, 'veg' => false, 'spice' => 3, 'desc' => 'Tangy onion sauce with vinegar, soy and potatoes. Served with rice.', 'img' => 'Vindaloo'],
                ['id' => 'saag-entree', 'cat' => 'entrees', 'name' => 'Saag (Spinach)', 'price' => 17.95, 'veg' => false, 'spice' => 1, 'desc' => 'Fresh spinach in a mildly spiced sauce. Served with rice.', 'img' => 'Saag'],
                // Nepali Special
                ['id' => 'gundruk', 'cat' => 'nepali-special', 'name' => 'Gundruk', 'price' => 14.50, 'veg' => true, 'spice' => 2, 'desc' => 'Potatoes, soybeans and dried spinach with Nepali spices. Served with rice.', 'img' => 'Gundruk'],
                ['id' => 'aloo-bodi-tama', 'cat' => 'nepali-special', 'name' => 'Aloo-Bodi-Tama', 'price' => 14.50, 'veg' => true, 'spice' => 2, 'desc' => 'Potatoes, bamboo shoot and black eye beans — originally Nepali taste.', 'img' => 'Aloo Bodi Tama'],
                ['id' => 'aloo-rayo-saag', 'cat' => 'nepali-special', 'name' => 'Aloo Rayo Ko Saag', 'price' => 14.50, 'veg' => true, 'spice' => 2, 'desc' => 'Potatoes and mustard greens with Nepalese spices.', 'img' => 'Aloo Rayo Ko Saag'],
                ['id' => 'goat-curry-nepali', 'cat' => 'nepali-special', 'name' => 'Goat Curry (Nepali)', 'price' => 19.95, 'veg' => false, 'spice' => 3, 'popular' => true, 'desc' => 'Nepali-style goat curry with onion, tomato, garlic and ginger.', 'img' => 'Goat Curry'],
                ['id' => 'sekuwa', 'cat' => 'nepali-special', 'name' => 'Sekuwa', 'price' => 17.99, 'veg' => false, 'spice' => 2, 'desc' => 'Marinated meat skewered and barbecued in the clay oven, served with chatpate.', 'img' => 'Sekuwa'],
                // Vegetarian
                ['id' => 'dal-makhani', 'cat' => 'vegetarian', 'name' => 'Dal Makhani', 'price' => 14.50, 'veg' => true, 'spice' => 1, 'popular' => true, 'desc' => 'Black lentils slow cooked with spices and butter.', 'img' => 'Dal Makhani'],
                ['id' => 'aloo-gobi', 'cat' => 'vegetarian', 'name' => 'Aloo Gobi', 'price' => 14.95, 'veg' => true, 'spice' => 1, 'popular' => true, 'desc' => 'Cauliflower and potatoes with garlic, onions and tomatoes.', 'img' => 'Aloo Gobi'],
                ['id' => 'chana-masala', 'cat' => 'vegetarian', 'name' => 'Chana Masala', 'price' => 14.50, 'veg' => true, 'spice' => 2, 'desc' => 'Garbanzo beans with onions, tomatoes and spices.', 'img' => 'Chana Masala'],
                ['id' => 'shahi-paneer', 'cat' => 'vegetarian', 'name' => 'Shahi Paneer / Tofu', 'price' => 14.95, 'veg' => true, 'spice' => 1, 'desc' => 'Paneer or tofu in a creamy tomato and onion sauce.', 'img' => 'Shahi Paneer'],
                ['id' => 'malai-kofta', 'cat' => 'vegetarian', 'name' => 'Malai Kofta', 'price' => 14.95, 'veg' => true, 'spice' => 1, 'desc' => 'Vegetable balls in a creamy tomato sauce.', 'img' => 'Malai Kofta'],
                // Tandoori
                ['id' => 'tandoori-chicken', 'cat' => 'tandoori', 'name' => 'Tandoori Chicken', 'price' => 18.50, 'veg' => false, 'spice' => 2, 'popular' => true, 'desc' => 'Bone-in chicken marinated in yogurt and spices, roasted in the clay oven.', 'img' => 'Tandoori Chicken'],
                ['id' => 'chicken-tikka', 'cat' => 'tandoori', 'name' => 'Chicken Tikka', 'price' => 18.99, 'veg' => false, 'spice' => 2, 'desc' => 'Boneless breast marinated and roasted in the tandoori oven.', 'img' => 'Chicken Tikka'],
                ['id' => 'lamb-boti', 'cat' => 'tandoori', 'name' => 'Lamb Boti', 'price' => 20.99, 'veg' => false, 'spice' => 2, 'desc' => 'Tender lamb marinated in yogurt, ginger and garlic.', 'img' => 'Lamb Boti'],
                ['id' => 'mixed-grill', 'cat' => 'tandoori', 'name' => 'Mixed Grilled', 'price' => 23.95, 'veg' => false, 'spice' => 2, 'desc' => 'Tandoori chicken, shrimp, chicken tikka and lamb boti.', 'img' => 'Mixed Grilled'],
                ['id' => 'paneer-tikka', 'cat' => 'tandoori', 'name' => 'Paneer Tikka', 'price' => 17.99, 'veg' => true, 'spice' => 1, 'desc' => 'Marinated Indian cheese baked in the tandoor clay oven.', 'img' => 'Paneer Tikka'],
                // Salad & Soup
                ['id' => 'green-salad', 'cat' => 'salad', 'name' => 'Fresh Green Salad', 'price' => 7.95, 'veg' => true, 'spice' => 0, 'desc' => 'Cucumber, onion, tomato, lettuce, carrots and chickpeas.', 'img' => 'Green Salad'],
                ['id' => 'daal-soup', 'cat' => 'soup', 'name' => 'Daal Soup', 'price' => 6.50, 'veg' => true, 'spice' => 1, 'desc' => 'Yellow lentils lightly cooked.', 'img' => 'Daal Soup'],
                ['id' => 'chicken-soup', 'cat' => 'soup', 'name' => 'Chicken Soup', 'price' => 7.99, 'veg' => false, 'spice' => 1, 'desc' => 'Mildly spiced lentil soup with chicken and cream.', 'img' => 'Chicken Soup'],
                // Breads
                ['id' => 'naan', 'cat' => 'breads', 'name' => 'Naan', 'price' => 2.95, 'veg' => true, 'spice' => 0, 'popular' => true, 'desc' => 'Indian style leavened white bread.', 'img' => 'Naan'],
                ['id' => 'garlic-naan', 'cat' => 'breads', 'name' => 'Garlic Naan', 'price' => 3.95, 'veg' => true, 'spice' => 0, 'popular' => true, 'desc' => 'Naan brushed with garlic — puffy, toasty, and perfect for curry.', 'img' => 'Garlic Naan'],
                // Desserts
                ['id' => 'gulab-jamun', 'cat' => 'desserts', 'name' => 'Gulab Jamun', 'price' => 4.50, 'veg' => true, 'spice' => 0, 'popular' => true, 'desc' => 'Cheese balls in lightly flavored syrup with pistachios.', 'img' => 'Gulab Jamun'],
                ['id' => 'mango-kulfi', 'cat' => 'desserts', 'name' => 'Mango Kulfi', 'price' => 2.99, 'veg' => true, 'spice' => 0, 'desc' => 'Indian ice cream made with fresh mangoes.', 'img' => 'Mango Kulfi'],
                ['id' => 'rice-pudding', 'cat' => 'desserts', 'name' => 'Rice Pudding', 'price' => 4.50, 'veg' => true, 'spice' => 0, 'desc' => 'Homemade rice pudding with sweet milk and pistachio.', 'img' => 'Rice Pudding'],
                // Sides
                ['id' => 'raita', 'cat' => 'sides', 'name' => 'Raita', 'price' => 2.50, 'veg' => true, 'spice' => 0, 'desc' => 'Cool homemade yogurt with fresh seasoning.', 'img' => 'Raita'],
                ['id' => 'mint-sauce', 'cat' => 'sides', 'name' => 'Mint Sauce', 'price' => 0.99, 'veg' => true, 'spice' => 0, 'desc' => 'Fresh mint chutney.', 'img' => 'Mint Sauce'],
                // Biryani
                ['id' => 'biryani-chicken', 'cat' => 'biryani', 'name' => 'Biryani Chicken', 'price' => 18.95, 'veg' => false, 'spice' => 2, 'popular' => true, 'desc' => 'Basmati rice slow cooked with biryani spices, onion and tomato.', 'img' => 'Biryani Chicken'],
                ['id' => 'biryani-lamb', 'cat' => 'biryani', 'name' => 'Biryani Lamb', 'price' => 21.50, 'veg' => false, 'spice' => 2, 'desc' => 'Aromatic basmati with lamb and biryani spices.', 'img' => 'Biryani Lamb'],
                ['id' => 'biryani-veg', 'cat' => 'biryani', 'name' => 'Biryani Vegetable', 'price' => 15.95, 'veg' => true, 'spice' => 1, 'desc' => 'Basmati rice with seasonal vegetables and biryani spices.', 'img' => 'Biryani Vegetable'],
                // Rice & Drinks
                ['id' => 'basmati-rice', 'cat' => 'rice', 'name' => 'Basmati Rice', 'price' => 2.50, 'veg' => true, 'spice' => 0, 'desc' => 'Steamed aromatic basmati rice.', 'img' => 'Basmati Rice'],
                ['id' => 'fried-rice', 'cat' => 'rice', 'name' => 'Fried Rice', 'price' => 9.95, 'veg' => false, 'spice' => 1, 'desc' => 'Basmati sautéed with cashew, almond and soy sauce.', 'img' => 'Fried Rice'],
                ['id' => 'lassi', 'cat' => 'drinks', 'name' => 'Lassi', 'price' => 3.99, 'veg' => true, 'spice' => 0, 'desc' => 'Traditional yogurt drink.', 'img' => 'Lassi'],
                ['id' => 'chai', 'cat' => 'drinks', 'name' => 'Tea', 'price' => 2.99, 'veg' => true, 'spice' => 0, 'popular' => true, 'desc' => 'Masala chai — the perfect way to finish your meal.', 'img' => 'Chai'],
                ['id' => 'mango-lassi', 'cat' => 'drinks', 'name' => 'Mango Lassi', 'price' => 4.50, 'veg' => true, 'spice' => 0, 'desc' => 'Sweet mango yogurt drink.', 'img' => 'Mango Lassi'],
            ],
        ];
    }
}

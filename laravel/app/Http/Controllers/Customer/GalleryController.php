<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\RestaurantData;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $cats = RestaurantData::galleryCats();
        $tab = $request->input('tab', 'all');

        if ($tab !== 'all' && ! collect($cats)->contains('id', $tab)) {
            $tab = 'all';
        }

        if ($tab === 'all') {
            $shown = [];
            foreach ($cats as $cat) {
                foreach ($cat['items'] as $item) {
                    $shown[] = [
                        'label' => $item['label'],
                        'cat' => $cat['name'],
                        'url' => $item['url'],
                    ];
                }
            }
        } else {
            $active = collect($cats)->firstWhere('id', $tab);
            $shown = array_map(
                fn (array $item) => [
                    'label' => $item['label'],
                    'cat' => $active['name'],
                    'url' => $item['url'],
                ],
                $active['items']
            );
        }

        return view('customer.gallery.index', [
            'cats' => $cats,
            'tab' => $tab,
            'shown' => $shown,
        ]);
    }
}

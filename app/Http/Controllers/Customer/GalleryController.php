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
                foreach ($cat['items'] as $label) {
                    $shown[] = ['label' => $label, 'cat' => $cat['name']];
                }
            }
        } else {
            $active = collect($cats)->firstWhere('id', $tab);
            $shown = array_map(
                fn (string $label) => ['label' => $label, 'cat' => $active['name']],
                $active['items']
            );
        }

        return view('customer.gallery.index', [
            'cats' => $cats,
            'tab' => $tab,
            'shown' => $shown,
            'spans' => [2, 1, 1, 1, 2, 1, 1, 1, 2, 1, 1, 1],
        ]);
    }
}

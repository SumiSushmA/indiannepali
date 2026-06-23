<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\RestaurantData;
use App\Services\Toast\ToastConfiguration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if ($url = ToastConfiguration::onlineOrderingUrl()) {
            return redirect()->away($url);
        }

        $menu = RestaurantData::menu();
        $query = strtolower(trim($request->input('q', '')));
        $vegOnly = $request->boolean('veg');
        $mode = $request->input('mode', 'delivery');

        if (! in_array($mode, ['delivery', 'pickup'], true)) {
            $mode = 'delivery';
        }

        $items = array_values(array_filter($menu['items'], function (array $item) use ($query, $vegOnly) {
            if ($vegOnly && empty($item['veg'])) {
                return false;
            }
            if ($query === '') {
                return true;
            }
            $haystack = strtolower($item['name'].' '.$item['desc']);

            return str_contains($haystack, $query);
        }));

        $categoryIds = array_unique(array_column($items, 'cat'));
        $categories = array_values(array_filter(
            $menu['categories'],
            fn (array $cat) => in_array($cat['id'], $categoryIds, true)
        ));

        return view('customer.menu.index', [
            'categories' => $categories,
            'items' => $items,
            'query' => $request->input('q', ''),
            'vegOnly' => $vegOnly,
            'mode' => $mode,
        ]);
    }
}

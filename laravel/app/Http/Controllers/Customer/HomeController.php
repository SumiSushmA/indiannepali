<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\RestaurantData;
use App\Support\StockImages;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $reserved = StockImages::homeReservedImagePaths();
        $popularItems = RestaurantData::popularItemsForHome($reserved, 6);

        foreach ($popularItems as $item) {
            if (! empty($item['image_path'])) {
                $reserved[] = $item['image_path'];
            }
        }

        return view('customer.home', [
            'bodyPage' => 'home',
            'popularItems' => $popularItems,
            'reviews' => RestaurantData::reviews(),
            'galleryPreview' => RestaurantData::galleryPreviewForHome($reserved, 5),
            'heroImage' => StockImages::hero(),
        ]);
    }
}

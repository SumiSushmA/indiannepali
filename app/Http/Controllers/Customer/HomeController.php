<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\RestaurantData;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('customer.home', [
            'bodyPage' => 'home',
            'popularItems' => RestaurantData::popularItems(6),
            'reviews' => RestaurantData::reviews(),
            'galleryPreview' => array_slice(RestaurantData::gallery(), 0, 5),
        ]);
    }
}

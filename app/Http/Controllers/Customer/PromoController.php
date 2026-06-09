<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\RestaurantData;
use Illuminate\View\View;

class PromoController extends Controller
{
    public function index(): View
    {
        return view('customer.promos.index', [
            'promos' => RestaurantData::promos(),
        ]);
    }
}

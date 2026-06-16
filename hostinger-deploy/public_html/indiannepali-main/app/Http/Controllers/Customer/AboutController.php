<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\RestaurantData;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        return view('customer.about.index', [
            'about' => RestaurantData::about(),
        ]);
    }
}

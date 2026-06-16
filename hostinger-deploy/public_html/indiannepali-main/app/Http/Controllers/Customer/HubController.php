<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HubController extends Controller
{
    public function index(): View
    {
        return view('customer.hub');
    }
}

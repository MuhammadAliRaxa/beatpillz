<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;

class HomeController extends Controller
{
    public function index()
    {
        if (settings('maintenance')->status && !authAdmin()) {
            return view('vendor.maintenance');
        } else {
            $homeSections = HomeSection::active()->get();
            return theme_view('home', ['homeSections' => $homeSections]);
        }
    }
}

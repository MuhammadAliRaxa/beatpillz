<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('reviewer.dashboard');
    }
}

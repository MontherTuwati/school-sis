<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Logic for admin dashboard
        return view('dashboard.admin.index');
    }
}
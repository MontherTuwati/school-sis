<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        // Logic for super admin dashboard
        return view('dashboard.superAdmin.index');
    }
}

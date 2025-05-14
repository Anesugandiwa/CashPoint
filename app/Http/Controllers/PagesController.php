<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
        return inertia('Dashboard');
    }

    public function dash(){
        return inertia('SuperAdmin/dashboard');
    }

    public function asset(){
        return inertia('SuperAdmin/Asset');
    }
}

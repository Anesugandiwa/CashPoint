<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return inertia('SuperAdmin/user');
    }

    public function loan(){
        return inertia('SuperAdmin/loanManagement');
    }
    public function form(){
        return inertia('loanForm');
    }

}

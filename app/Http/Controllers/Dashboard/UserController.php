<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('orders')->orderBy('created_at', 'desc')->paginate(20);

        return view('dashboards.users', compact('users'));
    }
}

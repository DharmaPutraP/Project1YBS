<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::pluck('name', 'id');
        return view('users.index', compact('roles'));
    }
}

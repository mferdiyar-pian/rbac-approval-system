<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RbacController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('rbac.index', [
            'user' => $user,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }
}

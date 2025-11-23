<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(\App\Models\User $user)
    {   
        $user->load(['movies','comments.movie']);

        return view('users.show', compact('user'));
    }
}

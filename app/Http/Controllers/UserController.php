<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {   
        $user->load(['movies','comments.movie']);

        return view('users.show', compact('user'));
    }
}

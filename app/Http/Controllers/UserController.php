<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request){
        $incomingFields = $request -> validate([
            'name' => ['required', 'min:3', 'max:16'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'max:20'],
        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);
        User::create($incomingFields);

        return "hello from controller";
    }
}

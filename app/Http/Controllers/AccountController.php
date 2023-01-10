<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $userDetails = $request->all();
        $userId = Account::create($userDetails);
        return response()->json(['success' => true,
            'message' => 'User created successfully', 'data' => $userId], 200);
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Account::validate($credentials)) {
            $userId = Account::where('email', $request->email)->first()->uuid;
            return response()->json(['success' => true,
                'message' => 'Logged in successfully', 'data' => $userId], 200);
        }

        return response()->json(['success' => false,
            'message' => 'Login unsuccessful'], 401);
    }

}

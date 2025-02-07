<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Users\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Rejestracja użytkownika.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:40|unique:user__users',
            'password' => 'required|string|min:8|confirmed',

            // 'name' => 'required|string|max:60',
            // 'surname' => 'required|string|max:60',

            'email' => 'required_without:phone|nullable|string|email|max:120|unique:user__users',
            'phone' => 'required_without:email|nullable|string|max:20|unique:user__users',

            // 'birthday' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'password'=> Hash::make($request->password),

            'name' => $request->name ?? null,
            'surname' => $request->surname ?? null,

            'email' => $request->email ?? null,
            'phone'=> $request->phone ?? null,
            'birthday' => $request->birthday ?? null
        ]);

        $token = $user->createToken('Personal Access Token')->accessToken;

        return response()->json($user, 201)->cookie(
            'tutor_access_token', $token, 60 * 24 * 7, '/', null, true, true
        );

    }

    /**
     * Logowanie użytkownika.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $credentials = $request->only('login', 'password');

        if (!$credentials['login'] || !$credentials['password']) {
            return response()->json(['error' => 'Email, username, or phone and password are required'], 400);
        }

        $user = User::where('email', $credentials['login'])
                    ->orWhere('username', $credentials['login'])
                    ->orWhere('phone', $credentials['login'])
                    ->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if (Auth::attempt(['id' => $user->id, 'password' => $credentials['password']])) {
            $token = $user->createToken('Personal Access Token')->accessToken;

            return response()->json($user, 201)->cookie(
                'tutor_access_token', $token, 60 * 24 * 7, '/', null, true, true
            );
        } else {
            return response()->json(['error' => 'Bad credentials'], 401);
        }
    }



    public function me(Request $request)
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated',
            ], 401);
        }
    
        return response()->json($user);
    }

    /**
     * Wylogowanie użytkownika.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
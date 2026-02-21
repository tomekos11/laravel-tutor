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
     * Register a new user and return a personal access token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:40|unique:user__users',
            'password' => 'required|string|min:8|confirmed',
            'email'    => 'required_without:phone|nullable|string|email|max:120|unique:user__users',
            'phone'    => 'required_without:email|nullable|string|max:20|unique:user__users',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'name'     => $request->name ?? null,
            'surname'  => $request->surname ?? null,
            'email'    => $request->email ?? null,
            'phone'    => $request->phone ?? null,
            'birthday' => $request->birthday ?? null
        ]);

        $token = $user->createToken('Personal Access Token')->accessToken;

        return apiResponse($user, "User registered successfully", true, 201)->cookie(
            'tutor_access_token', $token, 60 * 24 * 7, '/', null, true, true, false, 'Lax'
        );
    }

    /**
     * Authenticate a user using email, username, or phone and return a token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('login', 'password');

        if (!$credentials['login'] || !$credentials['password']) {
            return apiResponse(null, 'Email, username, or phone and password are required', false, 400);
        }

        $user = User::where(function($query) use ($credentials) {
            $query->where('email', $credentials['login'])
                  ->orWhere('username', $credentials['login'])
                  ->orWhere('phone', $credentials['login']);
        })->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return apiResponse(null, 'Invalid credentials', false, 401);
        }

        $token = $user->createToken('Personal Access Token')->accessToken;

        $cookie = cookie('tutor_access_token', $token, 60 * 60 * 24 * 7, '/', null, true, true);

        return apiResponse($user, "Logged in successfully", true, 200)->withCookie($cookie);
    }

    /**
     * Return the currently authenticated user's details.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return apiResponse(null, 'User not authenticated', false, 401);
        }

        return apiResponse($user, 'User details fetched successfully', true, 200);
    }

    /**
     * Update the authenticated user's profile.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return apiResponse(null, 'User not authenticated', false, 401);
        }

        $validator = Validator::make($request->all(), [
            'name'    => 'nullable|string|max:120',
            'surname' => 'nullable|string|max:120',
            'email'   => 'nullable|string|email|max:120|unique:user__users,email,' . $user->id,
            'phone'   => 'nullable|string|max:20|unique:user__users,phone,' . $user->id,
            'birthday'=> 'nullable|date',
            'image'   => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $user->fill($request->only(['name', 'surname', 'email', 'phone', 'birthday', 'image']));
        $user->save();

        return apiResponse($user, 'Profile updated successfully', true, 200);
    }

    /**
     * Logout the current user and revoke their access token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->token()->revoke();
        }

        $cookie = cookie('tutor_access_token', '', 0, '/', null, true, true);

        return apiResponse(null, 'Successfully logged out', true, 200)->withCookie($cookie);
    }
}

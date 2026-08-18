<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Users\Models\User;
use Modules\Users\Models\Role;
use Modules\Users\Models\UserRole;
use Modules\Users\Models\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
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
            'role'     => 'nullable|string|in:student,tutor,parent',
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

        $roleName = $request->role ?? 'student';
        $role = Role::where('name', $roleName)->first();
        if ($role) {
            UserRole::firstOrCreate(['user_id' => $user->id, 'role_id' => $role->id]);
        }

        if ($user->email) {
            $greeting = $user->name ? "Cześć {$user->name}," : 'Cześć,';

            try {
                Mail::raw(
                    "{$greeting}\n\nDziękujemy za założenie konta w TutorApp (login: {$user->username}).\n"
                    . "Możesz się teraz zalogować i znaleźć korepetytora, dołączyć do grup zajęciowych "
                    . "albo zacząć uczyć innych.\n\nDo zobaczenia!\nZespół TutorApp",
                    function ($message) use ($user) {
                        $message->to($user->email)->subject('Witaj w TutorApp!');
                    }
                );
            } catch (\Throwable $e) {
                // Brak/awaria SMTP nie powinna blokować rejestracji konta.
                Log::warning('Failed to send welcome email', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }
        }

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

        $user->load('userRoles.role');
        $user->roles_list = $user->userRoles->pluck('role.name');

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
            'role'    => 'nullable|string|in:student,tutor,parent',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $user->fill($request->only(['name', 'surname', 'email', 'phone', 'birthday', 'image']));
        $user->save();

        if ($request->filled('role')) {
            $role = Role::where('name', $request->role)->first();
            if ($role) {
                // Konto ma dokładnie jedną z ról student/tutor/parent naraz.
                UserRole::where('user_id', $user->id)
                    ->whereIn('role_id', Role::whereIn('name', ['student', 'tutor', 'parent'])->pluck('id'))
                    ->delete();
                UserRole::firstOrCreate(['user_id' => $user->id, 'role_id' => $role->id]);
            }
        }

        return apiResponse($user, 'Profile updated successfully', true, 200);
    }

    /**
     * Send a 6-digit password reset code to the user's e‑mail or phone.
     * Always responds with success (no account enumeration), even when no
     * matching user is found.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $login = $request->input('login');

        $user = User::where('email', $login)->orWhere('phone', $login)->first();

        if ($user) {
            $channel = $user->email === $login ? 'email' : 'phone';

            $code = (string) random_int(100000, 999999);

            PasswordReset::create([
                'user_id' => $user->id,
                'channel' => $channel,
                'code' => $code,
                'expires_at' => Carbon::now()->addMinutes(15),
            ]);

            if ($channel === 'email') {
                try {
                    Mail::raw(
                        "Twój kod do zresetowania hasła w TutorApp: {$code}\n\nKod jest ważny przez 15 minut. Jeśli to nie Ty, zignoruj tę wiadomość.",
                        function ($message) use ($user) {
                            $message->to($user->email)->subject('Reset hasła – TutorApp');
                        }
                    );
                } catch (\Throwable $e) {
                    Log::warning('Failed to send password reset email', ['user_id' => $user->id, 'error' => $e->getMessage()]);
                }
            } else {
                // Brak bramki SMS – na razie logujemy kod, żeby dało się przetestować przepływ.
                Log::info('Password reset SMS code (no SMS gateway configured)', [
                    'user_id' => $user->id,
                    'phone' => $user->phone,
                    'code' => $code,
                ]);
            }
        }

        return apiResponse(null, 'Jeśli podane dane są poprawne, wysłaliśmy kod resetujący hasło.', true, 200);
    }

    /**
     * Verify a password reset code and set a new password.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $login = $request->input('login');
        $user = User::where('email', $login)->orWhere('phone', $login)->first();

        if (!$user) {
            return apiResponse(null, 'Nieprawidłowy kod lub dane.', false, 422);
        }

        $reset = PasswordReset::where('user_id', $user->id)
            ->where('code', $request->input('code'))
            ->latest('id')
            ->first();

        if (!$reset || !$reset->isValid()) {
            return apiResponse(null, 'Kod jest nieprawidłowy lub wygasł.', false, 422);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        $reset->used_at = Carbon::now();
        $reset->save();

        return apiResponse(null, 'Hasło zostało zmienione. Możesz się teraz zalogować.', true, 200);
    }

    /**
     * Upload / replace the authenticated user's profile photo.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAvatar(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return apiResponse(null, 'User not authenticated', false, 401);
        }

        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $this->deleteAvatarFile($user->image);

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->image = Storage::disk('public')->url($path);
        $user->save();

        return apiResponse($user, 'Profile photo updated successfully', true, 200);
    }

    /**
     * Remove the authenticated user's profile photo.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAvatar(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return apiResponse(null, 'User not authenticated', false, 401);
        }

        $this->deleteAvatarFile($user->image);
        $user->image = null;
        $user->save();

        return apiResponse($user, 'Profile photo removed successfully', true, 200);
    }

    /**
     * Delete a previously uploaded avatar file from the public disk, if it
     * points at one (ignores externally hosted image URLs).
     */
    private function deleteAvatarFile(?string $image): void
    {
        if (!$image) {
            return;
        }

        $marker = '/storage/avatars/';
        $position = strpos($image, $marker);

        if ($position === false) {
            return;
        }

        $relativePath = 'avatars/' . substr($image, $position + strlen($marker));
        Storage::disk('public')->delete($relativePath);
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

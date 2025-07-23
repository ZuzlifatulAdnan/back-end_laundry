<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_handphone' => [
                'required',
                'regex:/^(\+62|62|0)[0-9]{9,13}$/',
                'unique:users,no_handphone',
            ],
            'password' => 'required|string|min:8|confirmed',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // PERUBAHAN: Tambahkan validasi gambar
        ], [
            'no_handphone.regex' => 'No handphone harus diawali dengan 62 dan hanya angka',
        ]);

        $imagePath = null; // Inisialisasi variabel
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('img/user/', $imagePath);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => 'Customer',
            'no_handphone' => $validated['no_handphone'],
            'password' => Hash::make($validated['password']),
            'image' => $imagePath, // PERBAIKAN: Menyimpan path gambar saat user dibuat
        ]);
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Login the user and issue token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            // UBAH BAGIAN INI
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials are incorrect.'
            ], 401); // 401 Unauthorized
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,                      // Tambahkan ini
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * Logout the user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Send forgot password email
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => __($status)
            ]);
        }

        return response()->json([
            'message' => __($status)
        ], 400);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => __($status)
            ]);
        }

        return response()->json([
            'message' => __($status)
        ], 400);
    }
}

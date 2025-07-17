<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
     /**
     * Tampilkan profil user yang login
     */
    public function show(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    /**
     * Update profil user
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_handphone' => 'required|string|min:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->no_handphone = $validated['no_handphone'];

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // hapus gambar lama jika ada
            if ($user->image) {
                $oldPath = public_path('img/user/' . $user->image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // simpan gambar baru
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/user'), $filename);

            $user->image = $filename;
        }

        $user->save();

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $user
        ]);
    }

    /**
     * Ganti password
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Password saat ini salah'
            ], 422);
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json([
            'message' => 'Password berhasil diperbarui'
        ]);
    }
}

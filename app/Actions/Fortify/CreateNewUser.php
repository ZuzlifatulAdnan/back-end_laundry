<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Http\UploadedFile;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'no_handphone' => ['required', 'string', 'max:15'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ])->validate();

        // Menyimpan gambar jika ada
        $imagePath = null;
        if (isset($input['image']) && $input['image'] instanceof UploadedFile) {
            $image = $input['image'];
            $imagePath = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/user/'), $imagePath);
        }

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'no_handphone' => $input['no_handphone'],
            'image' => $imagePath,
            'role' => 'Customer',
        ]);
    }
}

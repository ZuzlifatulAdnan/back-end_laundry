<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type_menu = 'user';

        // ambil data dari tabel user berdasarkan nama jika terdapat request
        $keyword = trim($request->input('name'));
        $role = $request->input('role');

        // Query users dengan filter pencarian dan role
        $users = User::when($keyword, function ($query, $name) {
            $query->where('name', 'like', '%' . $name . '%');
        })
            ->when($role, function ($query, $role) {
                $query->where('role', $role);
            })
            ->latest()
            ->paginate(10);

        // Tambahkan parameter query ke pagination
        $users->appends(['name' => $keyword, 'role' => $role]);

        // arahkan ke file pages/users/index.blade.php
        return view('pages.users.index', compact('type_menu', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $type_menu = 'user';

        // arahkan ke file pages/users/create.blade.php
        return view('pages.users.create', compact('type_menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi data dari form tambah user
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required',
            'no_handphone' =>'nullable',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif'
        ]);
        // Handle the image upload if present
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('img/user/', $imagePath);
        }
        //masukan data kedalam tabel users
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
            'no_handphone'=> $validatedData['no_handphone'],
            'image' => $imagePath, // Store the image path if available
        ]);

        //jika proses berhsil arahkan kembali ke halaman users dengan status success
        return Redirect::route('user.index')->with('success', 'User berhasil di tambah.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(User $user)
    {
        $type_menu = 'user';

        // arahkan ke file pages/users/edit
        return view('pages.users.edit', compact('user', 'type_menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, User $user)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id, // Exclude current user email
            'password' => 'nullable|min:8', // Password should be nullable
            'role' => 'required',
            'no_handphone'=> 'nullable',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif'
        ]);

        // Update the user data
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'no_handphone'=> $request->no_handphone
        ]);

        if (!empty($request->password)) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('img/user/', $path);
            $user->update([
                'image' => $path
            ]);
        }

        return Redirect::route('user.index')->with('success', 'User berhasil di ubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return Redirect::route('user.index')->with('success', 'User berhasil di hapus.');
    }
    public function show($id)
    {
        $type_menu = 'user';
        $user = User::find($id);

        // arahkan ke file pages/users/edit
        return view('pages.users.show', compact('user', 'type_menu'));
    }
}


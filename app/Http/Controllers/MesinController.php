<?php

namespace App\Http\Controllers;

use App\Models\mesin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MesinController extends Controller
{
      /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type_menu = 'mesin';
        $keyword = trim($request->input('nama'));
        $type = $request->input('type');
        $status = $request->input('status');

        $mesins = mesin::when($request->nama, function ($query, $nama) {
            $query->where('nama', 'like', '%' . $nama . '%');
        })->when($type, function ($query, $type) {
                $query->where('type', $type);
            })->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()->paginate(10);

        $mesins->appends(['nama' => $keyword, 'type' => $type]);

        return view('pages.mesin.index', compact('type_menu', 'mesins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $type_menu = 'mesin';

        return view('pages.mesin.create', compact('type_menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'nama' => 'required|string',
            'type'=> 'required|string',
            'durasi' => 'required|numeric',
            'status'=> 'required|string',
        ]);

        $mesin = mesin::create([
            'nama' => $request->nama,
            'type'=> $request->type,
            'durasi' => $request->durasi,
            'status'=> $request->status,
        ]);

        //jika proses berhsil arahkan kembali ke halaman users dengan status success
        return Redirect::route('mesin.index')->with('success', 'Mesin '.$mesin->nama .' berhasil di tambah.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(mesin $mesin)
    {
        $type_menu = 'mesin';

        return view('pages.mesin.edit', compact('mesin', 'type_menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, mesin $mesin)
    {
        // Validate the form data
        $request->validate([
            'nama' => 'required|string',
            'type'=> 'required|string',
            'durasi' => 'required|numeric',
            'status'=> 'required|string',
        ]);

        $mesin->update([
            'nama' => $request->nama,
            'type'=> $request->type,
            'durasi' => $request->durasi,
            'status'=> $request->status,
        ]);

        return Redirect::route('mesin.index')->with('success', 'Mesin '.$mesin->nama .' berhasil di ubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(mesin $mesin)
    {
        $mesin->delete();
        return Redirect::route('mesin.index')->with('success', 'Mesin '.$mesin->nama .' berhasil di hapus.');
    }
}

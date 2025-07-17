<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\mesin;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    /**
     * Data beranda untuk dashboard (API)
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $mesinReady = mesin::where('status', 'Ready')->get();

        return response()->json([
            'user' => [
                'name' => $user->name,
                'image' => $user->image
                    ? url('/img/user/' . $user->image)
                    : null,
            ],
            'mesin_ready' => $mesinReady,
        ]);
    }
}

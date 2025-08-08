<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recap;

class RecapController extends Controller
{
    public function store(Request $request)
    {
        // Validasi sederhana
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:65535'],
        ]);

        $recap = new Recap();
        $recap->message = $validated['message'];
        $recap->save();

        return response()->json([
            'success' => true,
            'id' => $recap->id,
            'message' => 'Recap stored successfully.',
        ], 201);
    }
}

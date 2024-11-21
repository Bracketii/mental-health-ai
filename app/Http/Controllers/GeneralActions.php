<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralActions extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,gif|max:10240', // Max 10MB
        ]);

        try {
            // Store the avatar in the public disk
            $path = $request->file('avatar')->store('avatars', 'public');

            // Return the file path
            return response()->json(['path' => $path], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Avatar upload failed: ' . $e->getMessage()], 500);
        }
    }
}

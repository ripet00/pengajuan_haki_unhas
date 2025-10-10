<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    // Menampilkan profil user yang sedang login
    public function show() {
        return response()->json(Auth::user());
    }


    // Update profil user
    public function update(Request $request) {
        $user = Auth::user();


        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'faculty' => 'nullable|string|max:255',
            'phone_number' => 'sometimes|string|max:15|unique:users,phone_number,' . $user->id,
        ]);


        $user->update($validated);


        return response()->json(['message' => 'Profil berhasil diperbarui', 'data' => $user]);
    }
}
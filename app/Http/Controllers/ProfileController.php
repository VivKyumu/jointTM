<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use App\Models\User; 

             


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    
public function update(Request $request)
{
     /** @var \App\Models\User $user */
    $user = Auth::user();

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        'avatar' => 'nullable|file|mimes:jpg,jpeg,jfif,png,gif,webp|max:2048',
        'photo' => 'nullable|file|mimes:jpg,jpeg,jfif,png,gif,webp|max:2048',
        'current_password' => 'required_with:password|nullable|current_password',
        'password' => 'nullable|string|min:8|confirmed',
    ]);

    $user->name = $validated['name'];
    $user->email = $validated['email'];

    $photoUpload = $request->file('photo') ?? $request->file('avatar');

    if ($photoUpload) {
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        if (Schema::hasColumn('users', 'photo') && $user->photo && $user->photo !== $user->avatar) {
            Storage::disk('public')->delete($user->photo);
        }

        $path = $photoUpload->store('avatars', 'public');
        $user->avatar = $path;

        if (Schema::hasColumn('users', 'photo')) {
            $user->photo = $path;
        }
    }

    if (!empty($validated['password'])) {
        $user->password = Hash::make($validated['password']);
    }

    $user->save(); // ✅ Save to database

    if ($request->ajax()) {
        return response()->json(['message' => 'Profile updated successfully.']);
    }

    return back()->with('success', 'Profile updated successfully.');
}


public function edit()
{
    $user = Auth::user();

    return view('profile.edit', compact('user'));
}

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

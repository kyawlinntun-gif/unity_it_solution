<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        unset($user->password);

        return view('users.index', [
            'user' => $user
        ]);
    }

    public function update(Request $request)
    {

        $userId = Auth::user()->id;
        $user = User::findOrFail($userId);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'img' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if($request->hasFile('img')) {
            if ($user->img) {
                Storage::disk('public')->delete('img/users' . $user->img);
            }

            $image = $request->file('img');
            $filename = Str::slug($request->name) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('img/users', $filename, 'public');
    
            $user->img = $filename;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile was updated successfully!');
    }
}

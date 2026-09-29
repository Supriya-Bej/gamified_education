<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\StudentPreference;

class StudentProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Profile
    |--------------------------------------------------------------------------
    */

    public function edit()
    {
        $user = Auth::guard('student')->user();

        $preference = StudentPreference::where('user_id', $user->id)->first();

        return view('student.profile', compact('user', 'preference'));
    }


    /*
    |--------------------------------------------------------------------------
    | Update Profile
    |--------------------------------------------------------------------------
    */

    public function update(Request $request)
    {
        $user = Auth::guard('student')->user();

        $request->validate([
            'name' => 'required|string|max:255',

            'email' => 'required|email|max:255',

            'profile_picture' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Name & Email
        |--------------------------------------------------------------------------
        */

        $user->name = $request->name;
        $user->email = $request->email;


        /*
        |--------------------------------------------------------------------------
        | Upload New Profile Picture
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('profile_picture')) {

            // Store old picture path
            $oldPicture = $user->profile_picture;


            // Store new picture
            $newPicture = $request->file('profile_picture')
                ->store('profile-pictures', 'public');


            // Save new picture path
            $user->profile_picture = $newPicture;


            /*
            |--------------------------------------------------------------------------
            | Delete Old Picture
            |--------------------------------------------------------------------------
            */

            if ($oldPicture) {
                Storage::disk('public')->delete($oldPicture);
            }
        }


        $user->save();


        return redirect()
            ->route('student.profile')
            ->with('success', 'Profile updated successfully!');
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Profile Picture
    |--------------------------------------------------------------------------
    */

    public function deletePicture()
    {
        $user = Auth::guard('student')->user();


        /*
        |--------------------------------------------------------------------------
        | Delete Picture From Storage
        |--------------------------------------------------------------------------
        */

        if ($user->profile_picture) {

            Storage::disk('public')
                ->delete($user->profile_picture);

        }


        /*
        |--------------------------------------------------------------------------
        | Remove Picture Path From Database
        |--------------------------------------------------------------------------
        */

        $user->profile_picture = null;

        $user->save();


        return redirect()
            ->route('student.profile')
            ->with('success', 'Profile picture removed successfully!');
    }
}
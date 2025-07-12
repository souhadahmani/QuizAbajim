<?php

namespace App\Http\Controllers\Enseignant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\SchoolLevel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; // Add this for file handling

class AccountController extends Controller
{
    public function edit()
    {
        $levels = SchoolLevel::all();
        return view('enseignant.quiz.accountsettings',compact('levels'));
    }

    public function updateProfile(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'fullName' => 'required|string|max:255',
            'eMail' => 'required|email|unique:users,email,' . $user->id,
            'pass'=>'required',
            'phone' => 'nullable|numeric|digits:8',
            'addRess' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate avatar upload
        ], [
            'phone.numeric' => 'يجب أن يحتوي حقل الهاتف على أرقام فقط.',
            'phone.digits' => 'يجب أن يحتوي حقل الهاتف على 8 أرقام.',
            'avatar.image' => 'الملف المرفوع يجب أن يكون صورة.',
            'avatar.mimes' => 'الصيغ المدعومة هي: jpeg, png, jpg, gif.',
            'avatar.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update the user data
        $user->full_name = $request->input('fullName');
        $user->email = $request->input('eMail');
        $user->password = $request->input('pass');
        $user->mobile = $request->input('phone');
        $user->address = $request->input('addRess');
        $user->role_name = $request->input('role_name');

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if it exists
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }

            // Store the new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath; // Update the avatar column with the new path
        }

        $user->save();

        // Redirect back with a success message
        return redirect()->back()->with('success_message', 'تم تحديث الملف الشخصي بنجاح');
    }
}
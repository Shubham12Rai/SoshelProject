<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Admin;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\Profile\UpdatePasswordProfileRequest;
use App\Http\Requests\Profile\UpdateAvatarProfileRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {

        $user = Auth::user();

        $roles = Role::all();

        // $roles_ids = Role::rolesUser($user);

        return view('profile.index', compact('user', 'roles'));
    }

    public function passwordUpdate(Request $request, $id)
    {
        $user = Admin::find($id);
        if (!$user) {
            $this->flashMessage('warning', 'User not found!', 'danger');
            return redirect()->route('user');
        }

        return view('profile.resetPassword', compact('user'));
    }

    // public function updateProfile(UpdateProfileRequest $request,$id)
    // {
    //     echo "Hello";die;
    // 	$user = Admin::find($id);

    //     if(!$user){
    //     	$this->flashMessage('warning', 'User not found!', 'danger');
    //         return redirect()->route('user');
    //     }

    //     if($user != Auth::user()){
    // 		$this->flashMessage('warning', 'Error updating profile!', 'danger');
    //         return redirect()->route('profile');
    // 	}

    //     $user->update($request->all());

    //     $this->flashMessage('check', 'Profile updated successfully!', 'success');

    //     return redirect()->route('profile');
    // }

    public function editProfileLoad(Request $request, $id)
    {
        $user = Admin::find($id);
        return view('profile.editProfile', compact('user'));
    }

    public function updateAdminProfile(Request $request, $id)
    {
        $rules = [
            'mobile' => 'required|numeric|digits:10', // Example: Mobile number must be 10 digits long and numeric
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // Redirect back with the validation errors and old input
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Admin::find($id);
        $name = $request->name;
        $mobile = $request->mobile;

        if (!$user) {
            $this->flashMessage('warning', 'User not found!', 'danger');
            return redirect()->route('user');
        }

        if ($user != Auth::user()) {
            $this->flashMessage('warning', 'Error updating profile!', 'danger');
            return redirect()->route('profile');
        }

        $user->name = $name;
        $user->mobile_number = $mobile;
        $user->save();

        $this->flashMessage('check', 'Profile updated successfully!', 'success');

        return redirect()->route('profile');
    }

    public function updatePassword(UpdatePasswordProfileRequest $request, $id)
    {
        $user = Admin::find($id);
        if (!$user) {
            $this->flashMessage('warning', 'User not found!', 'danger');
            return redirect()->route('user');
        }

        if ($user != Auth::user()) {
            $this->flashMessage('warning', 'Error updating password!', 'danger');
            return redirect()->route('profile');
        }


        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->route('profile')->withErrors($errors);
        }

        if (!Hash::check($request->old_password, $user->password)) {
            $this->flashMessage('warning', 'Wrong old password!', 'danger');
            return back()->withInput();
        }

        $request->merge(['password' => Hash::make($request->get('password'))]);

        $user->update($request->all());

        $this->flashMessage('check', 'Password updated successfully!', 'success');

        return redirect()->route('home');
    }

    public function updateAvatar(UpdateAvatarProfileRequest $request, $id)
    {
        $user = Admin::find($id);

        if (!$user) {
            $this->flashMessage('warning', 'User not found!', 'danger');
            return redirect()->route('user');
        }

        if ($user != Auth::user()) {
            $this->flashMessage('warning', 'Error updating avatar!', 'danger');
            return redirect()->route('profile');
        }

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg,ico|max:2048',
        ]);

        if ($request->file('avatar')) {
            $file = $request->file('avatar');
            $ext = $file->guessClientExtension();
            $path = $file->move("profiles/$id", "avatar.{$ext}");
            Admin::where('id', $id)->update(['avatar' => "profiles/$id/avatar.{$ext}"]);
        }

        $this->flashMessage('check', 'Avatar updated successfully!', 'success');

        return redirect()->route('profile');
    }
}
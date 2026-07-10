<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Get the currently authenticated admin.
     */
    private function getAdmin(): Admin
    {
        return Auth::guard('admin')->user();
    }

    /**
     * Show the profile page.
     */
    public function index()
    {
        $admin = $this->getAdmin();
        return view('admin.profile.index', compact('admin'));
    }

    /**
     * Update the admin's profile information.
     */
    public function update(Request $request)
    {
        $admin = $this->getAdmin();

        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email,' . $admin->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $admin->name  = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->save();

        return redirect()->route('admin.profile.index')
            ->with('success', 'Profile information updated successfully.');
    }

    /**
     * Update the admin's avatar photo.
     */
    public function updateAvatar(Request $request)
    {
        if ($request->filled('avatar_base64')) {
            $request->validate([
                'avatar_base64' => ['required', 'string'],
            ]);
        } else {
            $request->validate([
                'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            ]);
        }

        $admin = $this->getAdmin();

        // Remove old avatar if exists
        if ($admin->profile_photo && Storage::disk('public')->exists($admin->profile_photo)) {
            Storage::disk('public')->delete($admin->profile_photo);
        }

        if ($request->filled('avatar_base64')) {
            $base64Data = $request->input('avatar_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                $type = strtolower($type[1]);
                if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                    return back()->withErrors(['avatar' => 'Invalid image type.']);
                }
                $base64Data = base64_decode($base64Data);
                if ($base64Data === false) {
                    return back()->withErrors(['avatar' => 'Base64 decode failed.']);
                }
            } else {
                $base64Data = base64_decode($base64Data);
                $type = 'png';
            }
            
            $filename = 'admin_avatar_' . time() . '_' . uniqid() . '.' . $type;
            $path = 'admin/avatars/' . $filename;
            Storage::disk('public')->put($path, $base64Data);
        } else {
            $path = $request->file('avatar')->store('admin/avatars', 'public');
        }

        $admin->profile_photo = $path;
        $admin->save();

        return redirect()->route('admin.profile.index')
            ->with('success', 'Profile photo updated successfully.');
    }

    /**
     * Update the admin's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $admin = $this->getAdmin();

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->withInput();
        }

        $admin->password = Hash::make($request->password);
        $admin->save();

        return redirect()->route('admin.profile.index')
            ->with('success', 'Password changed successfully. Please login again if needed.');
    }
}

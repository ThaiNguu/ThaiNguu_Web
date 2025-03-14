<?php

namespace App\Http\Controllers\frontend;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserFrontendRequest;

class FrontendUserController extends Controller
{
    public function create()
    {
        return view('frontend.register');
    }

    public function store(StoreUserFrontendRequest $request)
    {
        try {
            $user = new User();
            $user->name = $request->name;
            if ($request->image) {
                $exten = $request->file('image')->extension();
                if (in_array($exten, ['jpg', 'png', 'gif', 'webp'])) {
                    $filename = $user->username . '.' . $exten;
                    $request->image->move(public_path('images/users'), $filename);
                    $user->image = $filename;
                }
            }
            // End upload
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->address = $request->address;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->roles = 'customer'; // Default role for frontend registration
            $user->status = 1; // Default status
            $user->created_at = now();
            $user->created_by = Auth::id() ?? 1;
            $user->save();

            return redirect()->route('frontend.user.create')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Registration failed!');
        }
    }
}

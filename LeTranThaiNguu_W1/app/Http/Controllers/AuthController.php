<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request)
{
    // Sử dụng 'email' và 'password' để đăng nhập
    $credentials = $request->only('email', 'password');

    try {
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();

        // Trả về token và thông tin người dùng
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'gender' => $user->gender,
                'phone' => $user->phone,
                'email' => $user->email,
                'roles' => $user->roles,
                'image' => $user->image,
                'address' => $user->address,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'status' => $user->status
            ]
        ]);
    } catch (JWTException $e) {
        return response()->json(['error' => 'Could not create token'], 500);
    }
}

 


    // Hàm đăng xuất
    public function logout(Request $request)
{
    try {
        // Kiểm tra xem token có hợp lệ không trước khi vô hiệu hóa
        $token = JWTAuth::getToken();
        if (!$token) {
            return response()->json(['error' => 'Token không hợp lệ'], 400);
        }

        // Vô hiệu hóa token
        JWTAuth::invalidate($token);

        return response()->json(['message' => 'Đăng xuất thành công']);
    } catch (JWTException $e) {
        return response()->json(['error' => 'Đăng xuất thất bại'], 500);
    }
}



    // Lấy thông tin người dùng
    public function getUser(Request $request)
    {
        $user = Auth::user();
        return response()->json($user);
    }
}

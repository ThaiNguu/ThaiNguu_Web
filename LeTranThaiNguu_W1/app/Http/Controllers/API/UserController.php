<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
class UserController extends Controller
{
    public function index()
    {
        $user = User::all();
        return UserResource::collection($user);
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user = new User();
            $user->name = $request->name;
            if ($request->image){
                $exten = $request->file("image")->extension();
                if (in_array($exten, ['jpg', 'png', 'gif', 'webp'])){
                    $filename = $user->slug . "." . $exten;
                    $request->image->move(public_path("images/users"), "$filename");
                    $user->image = "$filename";
                }
            }
            //end upload
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->address = $request->address;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->roles = $request->roles;
            $user->status = $request->status;
            $user->created_at = date('Y-m-d H:i:s');
            $user->created_by = Auth::id() ?? 1;
            $user->save();

        // Trả về phản hồi JSON với thông tin sản phẩm
        // return response()->json($user);

        return new UserResource($user);
    }

    public function show($id)
{
    $user = User::find($id);
    if ($user) {
        // Trả về dữ liệu dạng JSON
        return response()->json($user, 200);
    }

    return response()->json(['message' => 'User not found'], 404);
}


    public function edit(User $user)
    {
        //
    }

    public function update(Request $request, $id)
    {
        Log::info('Update request data:', $request->all());
        $user = User::find($id);
        if ($user == null) {
            return redirect()->route('admin.user.index');
        }
            $user->name = $request->name;
            //end upload
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->address = $request->address;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->roles = $request->roles;
            $user->status = $request->status;
            $user->updated_at = date('Y-m-d H:i:s');
            $user->updated_by = Auth::id() ?? 1;
            $user->save();
        
    }
    public function uploadImage(Request $request, $id)
    {
        // Kiểm tra xem có nhận được file hay không
        if ($request->hasFile('image')) {
            // Log tên file và kích thước
            Log::info('Image file name: ' . $request->file('image')->getClientOriginalName());
            Log::info('Image file size: ' . $request->file('image')->getSize());
    
            $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
    
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/users'), $imageName);
            $user->image = $imageName;
            $user->save();
    
            return response()->json(['message' => 'Image uploaded successfully']);
        }
    
        // Log thông báo nếu không có file
        Log::info('No image file uploaded');
        return response()->json(['message' => 'No image uploaded'], 400);
    }
    
    
    public function destroy(User $user)
    {
        $user->delete();
        return response(null, 204);
    }
    public function status(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->status = ($user->status == 2) ? 1 : 2;
        $user->save();

        return response()->json(['message' => 'User status updated successfully']);
    }
    public function delete(string $id)
{
    $user = User::find($id);
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Đặt trạng thái thành 0 khi xóa
    $user->status = 0; 
    $user->save();

    return response()->json(['message' => 'User status updated successfully']);
}


}

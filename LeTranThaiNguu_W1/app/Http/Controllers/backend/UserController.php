<?php
namespace App\Http\Controllers\backend;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = User::where('status', '!=', 0)
            ->select('id', 'name', 'phone', 'email','username','password','image','gender','roles','address','status')
            ->orderBy('created_at', 'desc')
            ->get();
        return view("backend.user.index", compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("backend.user.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try{
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
            return redirect()->route('admin.user.index');
    }catch (\Exception $e) {return redirect()->back();}
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if ($user == null)
        {
            return redirect()->route('admin.user.index');
        }
        return view("backend.user.show", compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        if ($user == null) {
            return redirect()->route('admin.user.index');
        }
        $list = User::where('status', '!=', 0)
        ->select('id', 'name', 'phone', 'email','username','password','gender','roles','address','status')
        ->orderBy('created_at', 'desc')
        ->get();
    return view("backend.user.edit", compact('list','user'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::find($id);
        if ($user == null) {
            return redirect()->route('admin.user.index');
        }
            $user->name = $request->name;
            if ($request->image){
                $exten = $request->file("image")->extension();
                if (in_array($exten, ['jpg', 'png', 'gif', 'webp'])){
                    $filename = $user->name . "." . $exten;
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
            $user->updated_at = date('Y-m-d H:i:s');
            $user->updated_by = Auth::id() ?? 1;
            $user->save();
            return redirect()->route('admin.user.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if($user==null)
        {
            return redirect()->route('admin.user.index');
        }
        $user->delete();
        return redirect()->route('admin.user.trash');
    }
    public function status(string $id)
    {
        $user = User::find($id);
        if($user==null)
        {
            return redirect()->route('admin.user.index');
        }
        $user->status = ($user->status==2)?1:2;
        $user->updated_at = date('Y-m-d H:i:s');
        $user->updated_by = Auth::id() ?? 1;
        $user->save();
        return redirect()->route('admin.user.index');
    }
    public function trash()
    {
        $list = User::where('status', '=', 0)
            ->select('id', 'name', 'phone', 'email','username','password','image','gender','roles','address','status')
            ->orderBy('created_at', 'desc')
            ->get();
        return view("backend.user.trash", compact('list'));
    }
    public function delete(string $id)
    {
        $user = User::find($id);
        if($user==null)
        {
            return redirect()->route('admin.user.index');
        }
    $user->status = 0;
    $user->updated_at = date('Ymd H:i:s');
    $user->updated_by = Auth::id() ?? 1;
    $user->save(); //Lưu
    return redirect()->route('admin.user.index');
    }
    public function restore(string $id)
    {
        $user = User::find($id);
        if($user==null)
        {
            return redirect()->route('admin.user.index');
        }
        $user->status = 1;
        $user->updated_at = date('Y-m-d H:i:s');
        $user->updated_by = Auth::id() ?? 1;
        $user->save();
        return redirect()->route('admin.user.trash');
    }

}

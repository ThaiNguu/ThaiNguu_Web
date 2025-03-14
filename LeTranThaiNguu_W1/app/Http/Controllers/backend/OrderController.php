<?php

namespace App\Http\Controllers\backend;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = Order::where('status', '!=', 0)
            ->select('id', 'delivery_name', 'delivery_address', 'delivery_phone', 'delivery_email','status')
            ->orderBy('created_at', 'desc')
            ->get();
        return view("backend.order.index", compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::find($id);
        if ($order == null)
        {
            return redirect()->route('admin.order.index');
        }
        return view("backend.order.show", compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::find($id);
        if ($order == null) {
            return redirect()->route('admin.order.index');
        }
        $list = Order::where('status', '!=', 0)
            ->select('id', 'delivery_name', 'delivery_address', 'delivery_phone', 'delivery_email','status')
            ->orderBy('created_at', 'desc')
            ->get();
        return view("backend.order.edit", compact('list', 'order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, string $id)
    {
        $order = Order::find($id);
        if ($order == null) {
            return redirect()->route('admin.order.index');
        }
        $order->delivery_name = $request->delivery_name;
        $order->delivery_email = $request->delivery_email;
        $order->delivery_phone = $request->delivery_phone;
        $order->delivery_address = $request->delivery_address;
        $order->note = $request->note;
        $order->type = $request->type;
        $order->status = $request->status;
        $order->updated_at = date('Y-m-d H:i:s');
        $order->updated_by = Auth::id() ?? 1;
        $order->save();
        return redirect()->route('admin.order.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function trash()
    {
        $list = Order::where('status', '=', 0)
            ->select('id', 'delivery_name', 'delivery_address', 'delivery_phone', 'delivery_email','status')
            ->orderBy('created_at', 'desc')
            ->get();
        return view("backend.order.trash", compact('list'));
    }
    public function delete(string $id)
    {
    $order = Order::find($id);

    if ($order == null) {
        return redirect()->route('admin.order.index');
    }
    $order->status = 0;
    $order->updated_at = date('Ymd H:i:s');
    $order->updated_by = Auth::id() ?? 1;
    $order->save(); //Lưu
    return redirect()->route('admin.order.index');
    }

    public function restore(string $id)
    {
        $order = Order::find($id);
        if($order==null)
        {
            return redirect()->route('admin.order.index');
        }
        $order->status = 1;
        $order->updated_at = date('Y-m-d H:i:s');
        $order->save();
        return redirect()->route('admin.order.trash');
    }
    public function status(string $id)
    {
        $order = Order::find($id);
        if($order==null)
        {
            return redirect()->route('admin.order.index');
        }
        $order->status = ($order->status==2)?1:2;
        $order->updated_at = date('Y-m-d H:i:s');
        $order->updated_by = Auth::id() ?? 1;
        $order->save();
        return redirect()->route('admin.order.index');
    }
}

<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
class OrderController extends Controller
{
    public function index()
    {
        $order = Order::all();
        return OrderResource::collection($order);
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $order = Order::create($request->all());
        return new OrderResource($order);
    }

    public function show($id)
    {
        $order = Order::find($id);
    if ($order) {
        // Trả về dữ liệu dạng JSON
        return response()->json($order, 200);
    }
    }
    public function edit(Order $order)
    {
        //
    }

    public function update(Request $request, $id)
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
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response(null, 204);
    }
    public function status(string $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->status = ($order->status == 2) ? 1 : 2;
        $order->save();

        return response()->json(['message' => 'Order status updated successfully']);
    }
    public function delete(string $id)
{
    $order = Order::find($id);
    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    // Đặt trạng thái thành 0 khi xóa
    $order->status = 0; 
    $order->save();

    return response()->json(['message' => 'Order status updated successfully']);
}


}

<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderDetailResource;
class OrderDetailController extends Controller
{
    public function index()
    {
        $Order = OrderDetail::all();
        return OrderDetailResource::collection($Order);
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $Order = OrderDetail::create($request->all());
        return new OrderDetailResource($Order);
    }

    public function show($id)
    {
        $Order = OrderDetail::find($id);
    if ($Order) {
        // Trả về dữ liệu dạng JSON
        return response()->json($Order, 200);
    }
    }
    public function edit(OrderDetail $Order)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $Order = OrderDetail::find($id);
        if ($Order == null) {
            return redirect()->route('admin.Order.index');
        }
        $Order->delivery_name = $request->delivery_name;
        $Order->delivery_email = $request->delivery_email;
        $Order->delivery_phone = $request->delivery_phone;
        $Order->delivery_address = $request->delivery_address;
        $Order->note = $request->note;
        $Order->type = $request->type;
        $Order->status = $request->status;
        $Order->updated_at = date('Y-m-d H:i:s');
        $Order->updated_by = Auth::id() ?? 1;
        $Order->save();
    }

    public function destroy(OrderDetail $Order)
    {
        $Order->delete();
        return response(null, 204);
    }
    public function status(string $id)
    {
        $Order = OrderDetail::find($id);
        if (!$Order) {
            return response()->json(['message' => 'OrderDetail not found'], 404);
        }

        $Order->status = ($Order->status == 2) ? 1 : 2;
        $Order->save();

        return response()->json(['message' => 'OrderDetail status updated successfully']);
    }
    public function delete(string $id)
{
    $Order = OrderDetail::find($id);
    if (!$Order) {
        return response()->json(['message' => 'OrderDetail not found'], 404);
    }

    // Đặt trạng thái thành 0 khi xóa
    $Order->status = 0; 
    $Order->save();

    return response()->json(['message' => 'OrderDetail status updated successfully']);
}


}

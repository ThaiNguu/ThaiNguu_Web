<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Orderdetail;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Danh sách thanh toán'], 200);
    }
    public function store(Request $request)
    {
        // Lấy dữ liệu đơn hàng từ request
        $data = $request->all();

        // Tạo đơn hàng
        $order = new Order();
        $order->user_id = $data['user_id'];
        $order->delivery_name = $data['delivery_name'];
        $order->delivery_email = $data['delivery_email'];
        $order->delivery_phone = $data['delivery_phone'];
        $order->delivery_address = $data['delivery_address'];
        $order->delivery_gender = $request->delivery_gender; 
        $order->note = $data['note'];
        $order->created_at = now();
        $order->type = $data['type']; // có thể là 'online' hoặc giá trị khác
        $order->status = '1'; // Trạng thái đơn hàng

        // Lưu đơn hàng
        if ($order->save()) {
            // Lưu chi tiết đơn hàng
            foreach ($data['products'] as $product) {
                $orderDetail = new Orderdetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $product['product_id'];
                $orderDetail->price = $product['price'];
                $orderDetail->qty = $product['qty'];
                $orderDetail->discount = $product['discount'];
                $orderDetail->amount = $product['amount'];
                $orderDetail->save();
            }

            return response()->json(['message' => 'Đơn hàng đã được tạo thành công.'], 201);
        }

        return response()->json(['message' => 'Không thể tạo đơn hàng.'], 500);
    }
}

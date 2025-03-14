<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Orderdetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CartController extends Controller
{
    public function index()
    {
        $list_cart = session('carts',[]);
        return view("frontend.cart",compact("list_cart"));
    }
    public function addcart()
    {
        $productid=$_GET["productid"];
        $qty=$_GET["qty"];
        $product=Product::find($productid);
        $cartitem = array(
            'id'=>$productid,
            'image'=>$product->image,
            'name'=>$product->name,
            'price'=>($product->pricesale>0)?$product->pricesale:$product->price,
            'qty'=>$qty
        );
        // luu vao session
        $carts=session('carts',[]);
        if(is_array($carts)&& count($carts)==0)
        {
            array_push($carts,$cartitem);
            session(['carts'=> $carts]);
        }
        else
        {
            $check=true;
            foreach($carts as $key=>$item)
            {
                if(in_array($productid,$item))
                {
                    $carts[$key]['qty']+=$qty;
                    $check=false;
                    break;
                }
            }
            if($check==true)
            {
                array_push($carts,$cartitem);
            }
            session(['carts'=>$carts]);
        }
        echo count($carts);
    }
    public function update(Request $request)
    {
    $carts = session('carts', []);
    $list_qty = $request->qty;
    foreach ($carts as $key => $cart) 
    {
        foreach($list_qty as $productid=>$qtyvalue)
        {
            if($carts[$key]['id']==$productid)
            {
                $carts[$key]['qty']=$qtyvalue;
            }
        }
        
    }
    session(['carts' => $carts]);
    return redirect()->route('site.cart.index');
    }

    public function delete($id)
    {
        $carts = session('carts', []);
        foreach ($carts as $key => $cart) 
            {
                if($carts[$key]['id']==$id)
                {
                    unset($carts[$key]);
                }
        
            }
        session(['carts' => $carts]);
        return redirect()->route('site.cart.index');
    }

    public function checkout()
    {
        $list_cart = session('carts',[]);
        return view("frontend.checkout",compact("list_cart"));
    }
    public function docheckout(Request $request)
    {
        $user = Auth::user();
        $carts = session('carts', []);
        if(count($carts)>0)
        {
        $order = new Order();
        $order->user_id=$user->id;
        $order->delivery_name = $request->name;
        $order->delivery_gender=$user->gender;
        $order->delivery_email = $request->email;
        $order->delivery_phone = $request->phone;
        $order->delivery_address = $request->address;
        $order->note=$request->note;
        $order->created_at=date('Y-m-d H:i:s');
        $order->type='online';
        $order->status=2;
        if($order->save())
        {
            foreach($carts as $cart)
            {
                $orderdetail = new Orderdetail();
                $orderdetail->order_id=$order->id;
                $orderdetail->product_id=$cart['id'];
                $orderdetail->price=$cart['price'];
                $orderdetail->qty=$cart['qty'];
                $orderdetail->discount=0;
                $orderdetail->amount=$cart['price']*$cart['qty'];
                $orderdetail->save();
            }
        }
        session(['carts'=> []]); 
        }
        return view("frontend.checkout_message");
    }

}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index()
    {
     
        $totalRevenue = Order::where('status', 'paid')->sum('amount');

      
        $orders = Order::with(['user', 'game'])->latest()->paginate(10);
        
       
        return view('admin.orders.index', compact('orders', 'totalRevenue'));
    }
    public function approve($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $order->update(['status' => 'paid']); 

        return back()->with('success', 'Захиалга баталгаажлаа!');
    }
    public function destroy($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $order->delete();

        return back()->with('success', 'Захиалга устгагдлаа!');
    }
}
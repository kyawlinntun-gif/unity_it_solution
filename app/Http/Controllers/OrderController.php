<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index()
    {
        $orders = DB::table('item_user')
                    ->join('users', 'users.id', '=', 'item_user.user_id')
                    ->join('items', 'items.id', '=', 'item_user.item_id')
                    ->select('users.name As customer_name', 'items.name As item_name', 'items.price', 'item_user.paid')
                    ->get();

        $groupOrders = $orders->groupBy('customer_name')->map(function ($customer){
            return [
                'item_name' => $customer->groupBy('item_name')->map(function ($item) {
                   return [
                       'total_price' => $item->sum('price'),
                       'total_count' => $item->count(),
                   ];
                }),
                'item_paid' => $customer->pluck('paid')->unique()->toArray()
            ];
        });

        return view('orders.index', [
            'orders' => $groupOrders
        ]);
    }

    public function export()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
    }
}

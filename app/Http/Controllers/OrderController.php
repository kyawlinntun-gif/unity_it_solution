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
                    ->select('users.name As customer_name', 'items.name As item_name', 'items.description', 'items.price', 'item_user.paid')
                    ->get();

        return view('orders.index', [
            'orders' => $orders
        ]);
    }

    public function export()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
    }
}

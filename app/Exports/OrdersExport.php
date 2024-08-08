<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrdersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
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

        $excelOrders = collect();

        foreach($groupOrders as $customer => $items) {
            foreach($items['item_name'] as $item => $details) {
                $excelOrders->push([
                    'Customer Name' => $customer,
                    'Item Name' => $item,
                    'Total Price' => $details['total_price'],
                    'Total Count' => $details['total_count'],
                    'Item Paid' => $items['item_paid'][0] ? "Yes" : "No" 
                ]);
            }
        }

        return $excelOrders;
    }
}

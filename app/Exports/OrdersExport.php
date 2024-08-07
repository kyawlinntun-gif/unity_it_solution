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
                    ->select('users.name As customer_name', 'items.name As item_name', 'items.description', 'items.price', DB::raw('CASE WHEN item_user.paid = 1 THEN "Yes" ELSE "No" END'))
                    ->get();

        return $orders;
    }
}

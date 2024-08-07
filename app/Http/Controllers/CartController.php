<?php

namespace App\Http\Controllers;

use App\Exports\CartsExport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class CartController extends Controller
{

    public function show()
    {
        $userId = Auth::user()->id;
        $user = User::findOrFail($userId);
        $items = $user->items;

        return view('carts.index', [
            'items' => $items
        ]);
    }

    public function store(Request $request)
    {
        $userId = Auth::user()->id;
        $user = User::findOrFail($userId);
        
        $request->validate([
            'item_id' => ['required', 'exists:items,id']
        ]);
        
        $user->items()->attach($request->item_id);
        
        return response()->json(['success' => 'Item was added to cart!']);
    }

    public function destroy($item_id)
    {
        $userId = Auth::user()->id;
        $user = User::find($userId);

        DB::table('item_user')->where('user_id', $userId)->where('item_id', $item_id)->limit(1)->delete();

        return response()->json(['success' => 'Item was removed from cart!']);
    }

    public function paid()
    {
        $userId = Auth::user()->id;
        $itemUsers = DB::table('item_user')
                        ->where('item_user.user_id', '=', $userId)
                        ->get();

        foreach($itemUsers as $itemUser)
        {
            DB::table('item_user')
                ->where('item_user.user_id', '=', $userId)
                ->where('item_user.item_id', '=', $itemUser->item_id)
                ->update(['paid' => 1]);
        }

        return redirect()->back()->with('success', 'Items were paid successfully!');
    }

    public function export()
    {
        return Excel::download(new CartsExport, 'carts.xlsx');
    }
}

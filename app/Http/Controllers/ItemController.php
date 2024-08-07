<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Str;
use App\Exports\ItemsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('items.index', [
            'items' => $items
        ]);
    }

    public function show($id)
    {
        $item = Item::find($id);
        return view('items.show', [
            'item' => $item
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'img' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if($request->hasFile('img')) {
            $image = $request->file('img');
            $filename = Str::slug($request->name) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('img/items', $filename, 'public');
    
            Item::create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'img' => $filename
            ]);

        } else {
            Item::create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
            ]);

            return response()->json(['success' => 'Item was created without image successfully!']);
        }

        return response()->json(['success' => 'Item was created successfully!']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'img' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $item = Item::findOrFail($id);

        $item->name = $request->name;
        $item->price = $request->price;
        $item->description = $request->description;

        if($request->hasFile('img')) {
            if ($item->img) {
                Storage::disk('public')->delete('img/items' . $item->img);
            }

            $image = $request->file('img');
            $filename = Str::slug($request->name) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('img/items', $filename, 'public');
    
            $item->img = $filename;
        }

        $item->save();

        return redirect()->back()->with('success', "Item was updated successfully!");
    }
    

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        if($item->img !== 'default.png') {
            Storage::disk('public')->delete('img/items' . $item->img);
        }
        $item->delete();

        return response()->json(['success' => 'Item was deleted successfully!']);
    }

    public function export()
    {
        return Excel::download(new ItemsExport, 'items.xlsx');
    }
}

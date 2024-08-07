<?php

namespace App\Http\Controllers;

use App\Exports\CustomersExport;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::all()->where('is_admin', '!==', 1);

        return view('customers.index', [
            'customers' => $customers
        ]);
    }

    public function destroy($id)
    {
        $customer = User::find($id);
        $customerName = $customer->name;
        $customer->delete();
        $success = $customerName . " was deleted successfully!";
        return response()->json(['success' => $success]);
    }

    public function export()
    {
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }
}

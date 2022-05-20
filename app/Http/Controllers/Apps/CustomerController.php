<?php

namespace App\Http\Controllers\Apps;

use Inertia\Inertia;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::when(request()->q, function($customers){
            $customers = $customers->where('name', 'like', '%'.request()->q.'%');
        })->latest()->paginate(5);

        //return inertia
        return Inertia::render('Apps/Customers/Index',
        [
            'customers' => $customers
        ]);
    }

    public function create()
    {
        return Inertia::render('Apps/Customers/Create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'no_telp' => 'required|unique:customers',
            'address' => 'required'
        ], [
            'name.required' => 'Nama harus diisi',
            'no_telp.required' => 'Nomor Telepon harus diisi',
            'address.required' => 'Alamat harus diisi',
            'no_telp.unique' => 'Nomor Telepon telah digunakan'
        ]);

        Customer::create([
            'name' => $request->name,
            'no_telp' => $request->no_telp,
            'address' => $request->address,
        ]);

        return redirect()->route('apps.customers.index');
    }

    public function edit(customer $customer)
    {
        return Inertia::render('Apps/Customers/Edit', [
            'customer' => $customer,
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $this->validate($request, [
            'name' => 'required',
            'no_telp' => 'required|unique:customers,no_telp,'.$customer->id,
            'address' => 'required'
        ], [
            'name.required' => 'Nama harus diisi',
            'no_telp.required' => 'Nomor Telepon harus diisi',
            'address.required' => 'Alamat harus diisi',
            'no_telp.unique' => 'Nomor Telepon telah digunakan'
        ]);


        $customer->update([
            'name' => $request->name,
            'no_telp' => $request->no_telp,
            'address' => $request->address,
        ]);

        return redirect()->route('apps.customers.index');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        $customer->delete();

        return redirect()->route('apps.customers.index');
    }
}

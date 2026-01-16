<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $query = Customer::withCount('orders');
        
        // Search by name, phone, or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        
        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }
        
        $customers = $query->orderBy('name')->paginate(10);
        
        // Get unique cities for filter dropdown
        $cities = Customer::whereNotNull('city')->distinct()->pluck('city')->sort();
        
        return view('admin.customers.index', compact('customers', 'cities'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created customer.
     */
    public function store(StoreCustomerRequest $request)
    {
        Customer::create($request->validated());
        
        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        $customer->load(['orders' => function ($query) {
            $query->latest()->limit(10);
        }]);
        
        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());
        
        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil diperbarui!');
    }

    /**
     * Remove the specified customer.
     * Note: Orders will be deleted due to cascade on delete.
     */
    public function destroy(Customer $customer)
    {
        // Delete associated orders first (cascade)
        $customer->orders()->delete();
        $customer->delete();
        
        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Pelanggan beserta order terkait berhasil dihapus!');
    }
}

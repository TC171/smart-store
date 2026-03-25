<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of customer users.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'customer');

        if ($request->search) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%');
        }

        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $customers = $query->latest()->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer user.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created customer user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'status' => 'required|boolean',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'role' => 'customer',
            'status' => $request->status,
        ]);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Tạo tài khoản khách hàng thành công');
    }

    /**
     * Display the specified customer user.
     */
    public function show(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer user.
     */
    public function edit(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer user.
     */
    public function update(Request $request, User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$customer->id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'status' => 'required|boolean',
        ]);

        $customer->update($request->only([
            'name', 'email', 'phone', 'gender', 'date_of_birth',
            'address', 'city', 'country', 'postal_code', 'status',
        ]));

        return redirect()->route('admin.customers.index')
            ->with('success', 'Cập nhật tài khoản khách hàng thành công');
    }

    /**
     * Remove the specified customer user.
     */
    public function destroy(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Xóa tài khoản khách hàng thành công');
    }
}

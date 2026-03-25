<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of admin and staff users.
     */
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['admin', 'staff']);

        if ($request->search) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%');
        }

        if ($request->role && in_array($request->role, ['admin', 'staff'])) {
            $query->where('role', $request->role);
        }

        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $admins = $query->latest()->paginate(10);

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin/staff user.
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created admin/staff user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,staff',
            'status' => 'required|boolean',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Tạo tài khoản quản trị thành công');
    }

    /**
     * Display the specified admin/staff user.
     */
    public function show(User $admin)
    {
        if (! in_array($admin->role, ['admin', 'staff'])) {
            abort(404);
        }

        return view('admin.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified admin/staff user.
     */
    public function edit(User $admin)
    {
        if (! in_array($admin->role, ['admin', 'staff'])) {
            abort(404);
        }

        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified admin/staff user.
     */
    public function update(Request $request, User $admin)
    {
        if (! in_array($admin->role, ['admin', 'staff'])) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$admin->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,staff',
            'status' => 'required|boolean',
        ]);

        $admin->update($request->only(['name', 'email', 'phone', 'role', 'status']));

        return redirect()->route('admin.admins.index')
            ->with('success', 'Cập nhật tài khoản quản trị thành công');
    }

    /**
     * Remove the specified admin/staff user.
     */
    public function destroy(User $admin)
    {
        if (! in_array($admin->role, ['admin', 'staff'])) {
            abort(404);
        }

        if ($admin->id === auth()->id()) {
            return back()->with('error', 'Không thể xóa tài khoản của chính mình');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Xóa tài khoản quản trị thành công');
    }
}

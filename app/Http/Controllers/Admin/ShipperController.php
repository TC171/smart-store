<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Shippers\StoreShipperRequest;
use App\Http\Requests\Admin\Shippers\UpdateShipperRequest;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ShipperController extends Controller
{
    // ===================== QUẢN LÝ SHIPPER =====================

    public function index(Request $request)
    {
        $query = User::where('role', 'shipper');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Đếm số đơn theo trạng thái dùng relationship orders
        $shippers = $query->withCount([
            'assignedOrders',
            'assignedOrders as shipping_count'  => fn($q) => $q->where('status', 'shipping'),
            'assignedOrders as completed_count' => fn($q) => $q->where('status', 'completed'),
            'assignedOrders as failed_count'    => fn($q) => $q->where('status', 'failed_delivery'),
        ])->latest()->paginate(15);

        return view('admin.shippers.index', compact('shippers'));
    }

    public function create()
    {
        return view('admin.shippers.create');
    }

    public function store(StoreShipperRequest $request)
    {
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'role'     => 'shipper',
            'status'   => $request->status,
        ]);

        return redirect()->route('admin.shippers.index')
            ->with('success', 'Tạo tài khoản shipper thành công!');
    }

    public function edit(User $shipper)
    {
        abort_unless($shipper->role === 'shipper', 404);
        return view('admin.shippers.edit', compact('shipper'));
    }

    public function update(UpdateShipperRequest $request, User $shipper)
    {
        abort_unless($shipper->role === 'shipper', 404);

        $data = [
            'name'   => $request->name,
            'email'  => $request->email,
            'phone'  => $request->phone,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $shipper->update($data);

        return redirect()->route('admin.shippers.index')
            ->with('success', 'Cập nhật thông tin shipper thành công!');
    }

    public function destroy(User $shipper)
    {
        abort_unless($shipper->role === 'shipper', 404);

        if ($shipper->assignedOrders()->where('status', 'shipping')->exists()) {
            return back()->with('error', 'Không thể xóa shipper đang có đơn hàng cần giao!');
        }

        $shipper->delete();

        return redirect()->route('admin.shippers.index')
            ->with('success', 'Đã xóa tài khoản shipper!');
    }

    // ===================== PHÂN CÔNG & THEO DÕI =====================

    /**
     * Theo dõi tất cả đơn đang giao
     */
    public function deliveries(Request $request)
    {
        $query = Order::with(['user', 'shipper'])->whereNotNull('shipper_id');

        if ($request->shipper_id) {
            $query->where('shipper_id', $request->shipper_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['shipping', 'failed_delivery', 'completed']);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('shipping_name', 'like', '%' . $request->search . '%')
                  ->orWhere('shipping_phone', 'like', '%' . $request->search . '%');
            });
        }

        $orders    = $query->latest()->paginate(15);
        $shippers  = User::where('role', 'shipper')->where('status', 1)->get();

        return view('admin.shippers.deliveries', compact('orders', 'shippers'));
    }

    /**
     * Danh sách đơn confirmed chưa có shipper
     */
    public function assign(Request $request)
    {
        $query = Order::with('user')
            ->where('status', 'confirmed')
            ->whereNull('shipper_id');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('shipping_name', 'like', '%' . $request->search . '%')
                  ->orWhere('shipping_phone', 'like', '%' . $request->search . '%');
            });
        }

        $orders   = $query->latest()->paginate(15);
        $shippers = User::where('role', 'shipper')->where('status', 1)->get();

        return view('admin.shippers.assign', compact('orders', 'shippers'));
    }

    /**
     * Thực hiện phân công
     */
    public function assignStore(Request $request)
    {
        $request->validate([
            'order_id'   => 'required|exists:orders,id',
            'shipper_id' => 'required|exists:users,id',
            'note'       => 'nullable|string|max:500',
        ]);

        $shipper = User::where('id', $request->shipper_id)
            ->where('role', 'shipper')
            ->where('status', 1)
            ->firstOrFail();

        $order = Order::findOrFail($request->order_id);

        if ($order->status !== 'confirmed') {
            return back()->with('error', 'Chỉ phân công được đơn hàng ở trạng thái Đã xác nhận!');
        }

        $order->update([
            'shipper_id' => $shipper->id,
            'status'     => 'shipping',
            'note'       => $request->note ? ($order->note ? $order->note . "\n[Shipper] " . $request->note : '[Shipper] ' . $request->note) : $order->note,
        ]);

        return redirect()->route('admin.shippers.deliveries')
            ->with('success', "Đã phân công đơn #{$order->order_number} cho shipper {$shipper->name}!");
    }
}

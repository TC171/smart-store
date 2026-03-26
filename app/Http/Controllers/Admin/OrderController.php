<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Orders\UpdateOrderPaymentStatusRequest;
use App\Http\Requests\Admin\Orders\UpdateOrderStatusRequest;
use App\Models\InventoryHistory;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        $query = Order::with('user', 'items');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->search) {
            $query->where('order_number', 'like', '%'.$request->search.'%')
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', '%'.request('search').'%')
                        ->orWhere('email', 'like', '%'.request('search').'%');
                });
        }

        $orders = $query->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['user', 'items', 'items.variant']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        $this->authorize('update', $order);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Create inventory history based on status change
        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            // Order completed - already handled in seeder
        } elseif ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
            // Order cancelled - return stock
            foreach ($order->items as $item) {
                $variant = ProductVariant::find($item->product_variant_id);
                if ($variant) {
                    $previousStock = $variant->stock;
                    $variant->increment('stock', $item->quantity);

                    InventoryHistory::create([
                        'product_variant_id' => $variant->id,
                        'type' => 'return',
                        'quantity' => $item->quantity,
                        'previous_stock' => $previousStock,
                        'current_stock' => $variant->stock,
                        'reference_type' => 'order',
                        'reference_id' => $order->id,
                        'notes' => "Trả hàng từ đơn hàng #{$order->order_number}",
                        'user_id' => auth('admin')->id(),
                    ]);
                }
            }
        }

        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công');
    }

    public function updatePaymentStatus(UpdateOrderPaymentStatusRequest $request, Order $order)
    {
        $this->authorize('update', $order);

        $order->update(['payment_status' => $request->payment_status]);

        return back()->with('success', 'Cập nhật trạng thái thanh toán thành công');
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        if ($order->status !== 'cancelled') {
            return back()->with('error', 'Chỉ có thể xóa đơn hàng đã hủy');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Xóa đơn hàng thành công');
    }
}

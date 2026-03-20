<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryHistory;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = InventoryHistory::with(['productVariant.product', 'user'])
            ->latest();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by product variant
        if ($request->filled('product_variant_id')) {
            $query->where('product_variant_id', $request->product_variant_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $inventoryHistories = $query->paginate(15);

        $productVariants = ProductVariant::with('product')->get();

        return view('admin.inventory-history.index', compact('inventoryHistories', 'productVariants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productVariants = ProductVariant::with('product')->where('status', 1)->get();

        return view('admin.inventory-history.create', compact('productVariants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request) {
            $productVariant = ProductVariant::findOrFail($request->product_variant_id);
            $previousStock = $productVariant->stock;

            // Calculate new stock based on type
            if ($request->type === 'in') {
                $newStock = $previousStock + $request->quantity;
            } elseif ($request->type === 'out') {
                if ($previousStock < $request->quantity) {
                    throw new \Exception('Không đủ hàng trong kho');
                }
                $newStock = $previousStock - $request->quantity;
            } else { // adjustment
                $newStock = $request->quantity;
            }

            // Update product variant stock
            $productVariant->update(['stock' => $newStock]);

            // Create inventory history record
            InventoryHistory::create([
                'product_variant_id' => $request->product_variant_id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'previous_stock' => $previousStock,
                'current_stock' => $newStock,
                'reference_type' => 'manual',
                'notes' => $request->notes,
                'user_id' => auth()->id(),
            ]);
        });

        return redirect()->route('inventory-history.index')
            ->with('success', 'Cập nhật kho thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryHistory $inventoryHistory)
    {
        // Eager load relationships để tránh N+1 query
        $inventoryHistory->load(['productVariant.product', 'user']);

        // Sửa lại tên view thành 'show_new' để khớp với file blade của bạn
        return view('admin.inventory-history.show_new', compact('inventoryHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Inventory history records should not be editable
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Inventory history records should not be editable
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryHistory $inventoryHistory)
    {
        // Only allow deletion of manual entries
        if ($inventoryHistory->reference_type !== 'manual') {
            return back()->with('error', 'Không thể xóa bản ghi tự động');
        }

        // Reverse the stock change
        $productVariant = $inventoryHistory->productVariant;
        if ($inventoryHistory->type === 'in') {
            $productVariant->decrement('stock', $inventoryHistory->quantity);
        } elseif ($inventoryHistory->type === 'out') {
            $productVariant->increment('stock', $inventoryHistory->quantity);
        }

        $inventoryHistory->delete();

        return redirect()->route('inventory-history.index')
            ->with('success', 'Xóa bản ghi kho thành công');
    }
}
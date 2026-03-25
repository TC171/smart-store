<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\InventoryHistory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing data
        $users = User::where('role', 'customer')->get();
        $productVariants = ProductVariant::with('product')->where('status', 1)->whereHas('product')->get();
        $coupons = Coupon::where('status', 1)->get();

        if ($users->isEmpty() || $productVariants->isEmpty()) {
            $this->command->error('Không đủ dữ liệu để tạo đơn hàng. Vui lòng chạy các seeder khác trước.');

            return;
        }

        DB::transaction(function () use ($users, $productVariants, $coupons) {
            for ($i = 1; $i <= 10; $i++) {
                $user = $users->random();
                $orderItems = [];
                $totalAmount = 0;

                // Create 1-3 random order items
                $itemCount = rand(1, 3);
                $selectedVariants = $productVariants->random(min($itemCount, $productVariants->count()));

                foreach ($selectedVariants as $variant) {
                    $quantity = rand(1, 3);
                    $price = $variant->sale_price ?? $variant->price;
                    $subtotal = $price * $quantity;

                    $orderItems[] = [
                        'product_id' => $variant->product_id,
                        'product_variant_id' => $variant->id,
                        'product_name' => $variant->product->name,
                        'sku' => $variant->sku,
                        'price' => $price,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $totalAmount += $subtotal;

                    // Update stock and create inventory history
                    if ($variant->stock >= $quantity) {
                        $previousStock = $variant->stock;
                        $variant->decrement('stock', $quantity);

                        InventoryHistory::create([
                            'product_variant_id' => $variant->id,
                            'type' => 'sale',
                            'quantity' => $quantity,
                            'previous_stock' => $previousStock,
                            'current_stock' => $variant->stock,
                            'reference_type' => 'order',
                            'reference_id' => null, // Will be updated after order creation
                            'notes' => "Đơn hàng mẫu #{$i}",
                        ]);
                    }
                }

                // Apply coupon if available (30% chance)
                $coupon = null;
                $discountAmount = 0;
                if ($coupons->isNotEmpty() && rand(1, 10) <= 3) {
                    $coupon = $coupons->random();
                    if ($coupon->type === 'percent') {
                        $discountAmount = $totalAmount * ($coupon->value / 100);
                        if ($coupon->max_discount && $discountAmount > $coupon->max_discount) {
                            $discountAmount = $coupon->max_discount;
                        }
                    } else {
                        $discountAmount = $coupon->value;
                    }
                }

                $grandTotal = $totalAmount - $discountAmount + 30000; // Shipping fee

                // Create order
                $order = Order::create([
                    'order_number' => 'ORD-'.date('Ymd').'-'.str_pad($i, 3, '0', STR_PAD_LEFT),
                    'user_id' => $user->id,
                    'coupon_id' => $coupon ? $coupon->id : null,
                    'total_amount' => $totalAmount,
                    'shipping_fee' => 30000,
                    'discount_amount' => $discountAmount,
                    'tax_amount' => 0,
                    'grand_total' => $grandTotal,
                    'status' => collect(['pending', 'confirmed', 'shipping', 'completed'])->random(),
                    'payment_status' => collect(['unpaid', 'paid'])->random(),
                    'shipping_name' => $user->name,
                    'shipping_phone' => $user->phone ?? '0123456789',
                    'shipping_address' => $user->address ?? 'Địa chỉ mẫu',
                    'shipping_city' => $user->city ?? 'Hà Nội',
                    'shipping_district' => $user->address ? 'Quận 1' : null,
                    'shipping_country' => $user->country ?? 'Việt Nam',
                    'note' => rand(1, 5) === 1 ? 'Giao hàng cẩn thận' : null,
                    'ordered_at' => now()->subDays(rand(0, 30)),
                    'completed_at' => rand(1, 10) === 1 ? now()->subDays(rand(0, 7)) : null,
                ]);

                // Create order items
                foreach ($orderItems as $item) {
                    $item['order_id'] = $order->id;
                    OrderItem::create($item);
                }

                // Update inventory history with order ID
                InventoryHistory::where('reference_type', 'order')
                    ->where('notes', "Đơn hàng mẫu #{$i}")
                    ->update(['reference_id' => $order->id]);
            }
        });

        $this->command->info('Đã tạo thành công 10 đơn hàng mẫu!');
    }
}

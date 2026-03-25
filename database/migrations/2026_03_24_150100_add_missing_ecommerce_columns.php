<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (! Schema::hasColumn('categories', 'is_featured')) {
                $table->boolean('is_featured')->default(false);
            }
            if (! Schema::hasColumn('categories', 'status')) {
                $table->boolean('status')->default(true);
            }
            if (! Schema::hasColumn('categories', 'sort_order')) {
                $table->integer('sort_order')->default(0);
            }
            if (! Schema::hasColumn('categories', 'meta_title')) {
                $table->string('meta_title')->nullable();
            }
            if (! Schema::hasColumn('categories', 'meta_description')) {
                $table->text('meta_description')->nullable();
            }
            if (! Schema::hasColumn('categories', 'meta_keywords')) {
                $table->text('meta_keywords')->nullable();
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'price')) {
                $table->decimal('price', 12, 2)->nullable();
            }
            if (! Schema::hasColumn('products', 'sale_price')) {
                $table->decimal('sale_price', 12, 2)->nullable();
            }
            if (! Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(0);
            }
            if (! Schema::hasColumn('products', 'sold_count')) {
                $table->unsignedBigInteger('sold_count')->default(0);
            }
            if (! Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false);
            }
            if (! Schema::hasColumn('products', 'is_new')) {
                $table->boolean('is_new')->default(false);
            }
            if (! Schema::hasColumn('products', 'meta_title')) {
                $table->string('meta_title')->nullable();
            }
            if (! Schema::hasColumn('products', 'meta_description')) {
                $table->text('meta_description')->nullable();
            }
        });

        Schema::table('product_variants', function (Blueprint $table) {
            if (! Schema::hasColumn('product_variants', 'color')) {
                $table->string('color')->nullable()->after('sku');
            }
            if (! Schema::hasColumn('product_variants', 'storage')) {
                $table->string('storage')->nullable()->after('color');
            }
            if (! Schema::hasColumn('product_variants', 'ram')) {
                $table->string('ram')->nullable()->after('storage');
            }
            if (! Schema::hasColumn('product_variants', 'image')) {
                $table->string('image')->nullable()->after('stock');
            }
            if (! Schema::hasColumn('product_variants', 'status')) {
                $table->boolean('status')->default(true)->after('image');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (! Schema::hasColumn('order_items', 'order_id')) {
                $table->foreignId('order_id')->nullable()->constrained()->cascadeOnDelete();
            }
            if (! Schema::hasColumn('order_items', 'product_id')) {
                $table->foreignId('product_id')->nullable()->constrained()->cascadeOnDelete();
            }
            if (! Schema::hasColumn('order_items', 'product_variant_id')) {
                $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            }
            if (! Schema::hasColumn('order_items', 'product_name')) {
                $table->string('product_name')->nullable();
            }
            if (! Schema::hasColumn('order_items', 'sku')) {
                $table->string('sku')->nullable();
            }
            if (! Schema::hasColumn('order_items', 'price')) {
                $table->decimal('price', 12, 2)->default(0);
            }
            if (! Schema::hasColumn('order_items', 'quantity')) {
                $table->integer('quantity')->default(1);
            }
            if (! Schema::hasColumn('order_items', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        // Intentionally no-op to avoid dropping active production columns.
    }
};

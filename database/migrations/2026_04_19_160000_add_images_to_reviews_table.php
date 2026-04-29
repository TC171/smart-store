<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('reviews', 'images')) {
            Schema::table('reviews', function (Blueprint $table) {
                // Store multiple images as JSON array of paths
                $table->json('images')->nullable()->after('comment');
            });
        }
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};

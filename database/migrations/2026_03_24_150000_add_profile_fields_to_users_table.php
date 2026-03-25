<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('status');
            }
            if (! Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('phone');
            }
            if (! Schema::hasColumn('users', 'gender')) {
                $table->string('gender')->nullable()->after('avatar');
            }
            if (! Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('gender');
            }
            if (! Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('date_of_birth');
            }
            if (! Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (! Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable()->after('city');
            }
            if (! Schema::hasColumn('users', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('country');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['phone', 'avatar', 'gender', 'date_of_birth', 'address', 'city', 'country', 'postal_code'];
            $drop = array_values(array_filter($columns, fn ($column) => Schema::hasColumn('users', $column)));
            if (! empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('email');
            $table->string('phone')->nullable()->after('is_active');
            $table->foreignId('warehouse_id')->nullable()
                  ->after('phone');
            $table->string('avatar')->nullable()->after('warehouse_id');

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_active',
                'phone',
                'warehouse_id',
                'avatar',
            ]);
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_group_id')
                  ->constrained('attribute_groups')
                  ->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type', 20)->default('select');
            $table->string('color', 7)->nullable();
            $table->string('value')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_filterable')->default(true);
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('slug');
            $table->index('attribute_group_id');
            $table->index('is_filterable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
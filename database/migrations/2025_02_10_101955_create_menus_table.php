<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key sudah otomatis unique
            $table->string('name');
            $table->string('icon', 100)->nullable();
            $table->uuid('route_id')->nullable();
            $table->uuid('parent_id')->nullable();
            $table->unsignedInteger('sort_order')->nullable();
            $table->timestamps();
        });

        // Tambahkan foreign key setelah tabel dibuat
        Schema::table('menus', function (Blueprint $table) {
            $table->foreign('route_id')
                ->references('id')
                ->on('routes')
                ->onDelete('cascade');

            $table->foreign('parent_id')
                ->references('id')
                ->on('menus')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};

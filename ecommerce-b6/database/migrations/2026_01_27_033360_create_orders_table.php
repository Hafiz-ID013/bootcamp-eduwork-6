<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->id();

            // siapa yang beli
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // total harga
            $table->integer('total_price');

            // status transaksi
            $table->string('status')->default('paid');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

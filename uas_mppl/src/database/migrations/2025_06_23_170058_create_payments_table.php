<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->enum('method', ['tunai', 'kartu kredit', 'qris']);
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['belum bayar', 'lunas', 'gagal']);
            $table->dateTime('paid_at')->nullable();
            $table->string('proof_of_payment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

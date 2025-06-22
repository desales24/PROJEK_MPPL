<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->enum('method', ['cash', 'qr', 'debit', 'credit']);
            $table->integer('amount');
            $table->enum('status', ['paid', 'pending', 'failed'])->default('pending');
            $table->dateTime('paid_at')->nullable();
            $table->string('card_number')->nullable(); // Optional field for card payments
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

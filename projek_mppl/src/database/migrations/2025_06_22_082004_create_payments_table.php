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
            $table->enum('method', ['cash', 'qris', 'debit']);
            $table->integer('amount');
            $table->enum('status', ['paid', 'pending', 'failed'])->default('pending');
            $table->dateTime('paid_at')->nullable();
            $table->string('proof_of_payment')->nullable(); // Optional field for payment proof image
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

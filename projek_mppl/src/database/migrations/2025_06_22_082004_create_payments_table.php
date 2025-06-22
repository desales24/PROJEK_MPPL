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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->dateTime('payment_date')->useCurrent();
            $table->decimal('amount_paid', 10, 2);
            $table->enum('payment_method', ['cash', 'debit', 'dana', 'ovo', 'qris']);
            $table->string('proof_of_payment')->nullable(); // path ke file bukti
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

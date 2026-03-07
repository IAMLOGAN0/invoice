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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->foreignId('shop_id')->constrained();
            $table->foreignId('customer_id')->constrained();
            $table->date('invoice_date');
            $table->decimal('subtotal', 8, 2);
            $table->decimal('cgst', 8, 2);
            $table->decimal('sgst', 8, 2);
            $table->decimal('igst', 8, 2);
            $table->decimal('grand_total', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

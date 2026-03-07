<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('paid_amount', 10, 2)->default(0)->after('grand_total');
            $table->decimal('due_amount', 10, 2)->default(0)->after('paid_amount');
            $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('paid')->after('due_amount');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['paid_amount', 'due_amount', 'payment_status']);
        });
    }
};

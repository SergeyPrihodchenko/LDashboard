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
        Schema::create('fluidline_sateli_InvoiceCallList', function (Blueprint $table) {
            $table->string('client_code')->nullable()->default(null);
            $table->string('client_phone')->nullable()->default(null);
            $table->string('client_phone_id')->nullable()->default(null);
            $table->string('client_phone_date')->nullable()->default(null);
            $table->string('invoice_id')->nullable()->default(null);
            $table->integer('invoice_status')->nullable()->default(null);
            $table->string('invoice_number')->nullable()->default(null);
            $table->timestamp('invoice_date')->nullable()->default(null);
            $table->float('invoice_price', 20)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sateli_phones');
    }
};

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
        Schema::create('fluidline_hylok_InvoiceList', function (Blueprint $table) {
            $table->string('client_id')->nullable()->default(null);
            $table->string('fluid_tag')->nullable()->default(null);
            $table->string('client_mail')->nullable()->default(null);
            $table->string('client_mail_id')->nullable()->default(null);
            $table->string('client_code')->nullable()->default(null);
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
        Schema::dropIfExists('hylok_invoices');
    }
};

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
        Schema::create('fluidline_wika_visitors_info', function (Blueprint $table) {
            $table->string('vid')->nullable()->default(null);
            $table->string('user_agent')->nullable()->default(null);
            $table->string('ip')->nullable()->default(null);
            $table->string('fingerprint')->nullable()->default(null);
            $table->string('_ym_uid')->nullable()->default(null);
            $table->timestamp('created_on')->nullable()->default(null);
            $table->timestamp('visited_on')->nullable()->default(null);
            $table->text('geo')->nullable()->default(null);
            $table->integer('u_width')->nullable()->default(null);
            $table->string('client_id')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wika_visitors');
    }
};

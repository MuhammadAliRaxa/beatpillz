<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iap_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('store', 50)->default('app_store'); // 'app_store' | 'play_store'
            $table->string('product_id', 100);
            $table->string('transaction_id', 255)->unique(); // idempotency key
            $table->double('amount');
            $table->string('environment', 50)->default('production');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iap_transactions');
    }
};

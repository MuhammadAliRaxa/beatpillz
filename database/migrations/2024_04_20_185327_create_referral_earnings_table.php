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
        Schema::create('referral_earnings', function (Blueprint $table) {
            $table->id()->startingValue(1000);
            $table->bigInteger('referral_id')->unsigned();
            $table->bigInteger('author_id')->unsigned();
            $table->bigInteger('sale_id')->unsigned();
            $table->double('author_earning');
            $table->tinyInteger('status')->default(1)->comment('1:Active 2:Refunded 3:Cancelled');
            $table->foreign("referral_id")->references("id")->on('referrals')->onDelete('cascade');
            $table->foreign("author_id")->references("id")->on('users')->onDelete('cascade');
            $table->foreign("sale_id")->references("id")->on('sales')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_earnings');
    }
};

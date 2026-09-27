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
        Schema::create('premium_earnings', function (Blueprint $table) {
            $table->id()->startingValue(1000);
            $table->bigInteger('author_id')->unsigned();
            $table->bigInteger('subscription_id')->unsigned()->nullable();
            $table->bigInteger('item_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('percentage');
            $table->double('price');
            $table->double('author_earning');
            $table->foreign("author_id")->references("id")->on('users')->onDelete('cascade');
            $table->foreign("subscription_id")->references("id")->on('subscriptions')->onDelete('set null');
            $table->foreign("item_id")->references("id")->on('items')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('premium_earnings');
    }
};

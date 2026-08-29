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
        Schema::create('sales', function (Blueprint $table) {
            $table->id()->startingValue(1000);
            $table->bigInteger('author_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('item_id')->unsigned();
            $table->tinyInteger('license_type')->comment('1:Regular 2:Extended');
            $table->double('price');
            $table->double('buyer_fee')->default(0);
            $table->text('buyer_tax')->nullable();
            $table->double('author_fee')->default(0);
            $table->text('author_tax')->nullable();
            $table->double('author_earning')->default(0);
            $table->string('country', 10)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1:Active 2:Refunded 3:Cancelled');
            $table->foreign("author_id")->references("id")->on('users')->onDelete('cascade');
            $table->foreign("user_id")->references("id")->on('users')->onDelete('cascade');
            $table->foreign("item_id")->references("id")->on('items')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};

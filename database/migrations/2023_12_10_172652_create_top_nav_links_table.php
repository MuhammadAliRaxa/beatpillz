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
        Schema::create('top_nav_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('link');
            $table->tinyInteger('link_type')->default(1);
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->bigInteger('order')->default(0);
            $table->foreign('parent_id')->references('id')->on('top_nav_links')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_nav_links');
    }
};

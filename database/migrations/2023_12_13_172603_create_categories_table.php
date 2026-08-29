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
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->double('regular_buyer_fee')->default(0);
            $table->double('extended_buyer_fee')->default(0);
            $table->tinyInteger('file_type')->default(1)->comment('1:File With Preview Image  2:File With Video Preview  3:File With Audio Preview');
            $table->bigInteger('thumbnail_width')->default(120)->unsigned();
            $table->bigInteger('thumbnail_height')->default(120)->unsigned();
            $table->bigInteger('preview_image_height')->default(1200)->unsigned()->nullable();
            $table->bigInteger('preview_image_height')->default(610)->unsigned()->nullable();
            $table->bigInteger('maximum_screenshots')->default(15)->unsigned();
            $table->string('main_file_types')->default('zip,rar,pdf');
            $table->bigInteger('max_preview_file_size')->default(10)->unsigned();
            $table->bigInteger('views')->default(0);
            $table->bigInteger('sort_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

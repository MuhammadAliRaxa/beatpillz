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
        Schema::create('item_updates', function (Blueprint $table) {
            $table->bigIncrements('id')->startingValue(1000);
            $table->bigInteger('author_id')->unsigned();
            $table->bigInteger('item_id')->unsigned();
            $table->string('name', 100)->unique();
            $table->longText('description');
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('sub_category_id')->unsigned()->nullable();
            $table->longText('options')->nullable();
            $table->text('demo_link')->nullable();
            $table->longText('tags');
            $table->string('thumbnail')->nullable();
            $table->string('preview_type')->default('image');
            $table->string('preview_image')->nullable();
            $table->string('preview_video')->nullable();
            $table->string('preview_audio')->nullable();
            $table->string('main_file')->nullable();
            $table->boolean('is_main_file_external')->default(false);
            $table->longText('screenshots')->nullable();
            $table->double('regular_price')->nullable();
            $table->double('extended_price')->nullable();
            $table->boolean('is_supported')->default(0);
            $table->text('support_instructions')->nullable();
            $table->boolean('purchasing_status')->default(true);
            $table->boolean('is_free')->default(false);
            $table->foreign("author_id")->references("id")->on('users')->onDelete('cascade');
            $table->foreign("item_id")->references("id")->on('items')->onDelete('cascade');
            $table->foreign("category_id")->references("id")->on('categories')->onDelete('cascade');
            $table->foreign("sub_category_id")->references("id")->on('sub_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_updates');
    }
};

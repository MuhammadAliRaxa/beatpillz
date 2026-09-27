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
        Schema::create('badges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('alias');
            $table->string('title')->nullable();
            $table->string('image');
            $table->string('country', 10)->unique()->nullable();
            $table->bigInteger('level_id')->unsigned()->unique()->nullable();
            $table->bigInteger('membership_years')->unique()->nullable();
            $table->boolean('is_permanent')->default(false);
            $table->foreign("level_id")->references("id")->on('levels')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};

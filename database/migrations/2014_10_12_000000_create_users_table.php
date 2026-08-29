<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname', 50)->nullable();
            $table->string('lastname', 50)->nullable();
            $table->string('username', 50)->unique()->nullable();
            $table->string('email', 100)->unique()->nullable();
            $table->text('address')->nullable();
            $table->string('password')->nullable();
            $table->string('api_key')->nullable();
            $table->boolean('is_author')->default(false);
            $table->boolean('is_featured_author')->default(false);
            $table->double('balance')->default(0);
            $table->unsignedBigInteger('level_id')->nullable();
            $table->enum('exclusivity', ['exclusive', 'non_exclusive'])->nullable();
            $table->bigInteger('total_sales')->default(0)->unsigned();
            $table->double('total_sales_amount')->default(0);
            $table->double('total_referrals_earnings')->default(0);
            $table->bigInteger('total_reviews')->default(0)->unsigned();
            $table->bigInteger('avg_reviews')->default(0)->unsigned();
            $table->bigInteger('total_followers')->default(0)->unsigned();
            $table->bigInteger('total_following')->default(0)->unsigned();
            $table->string('avatar')->nullable();
            $table->string('profile_cover')->nullable();
            $table->string('profile_heading')->nullable();
            $table->longText('profile_description')->nullable();
            $table->string('profile_contact_email')->nullable();
            $table->text('profile_social_links')->nullable();
            $table->string('facebook_id')->unique()->nullable();
            $table->string('google_id')->unique()->nullable();
            $table->string('microsoft_id')->unique()->nullable();
            $table->string('vkontakte_id')->unique()->nullable();
            $table->string('envato_id')->unique()->nullable();
            $table->string('github_id')->unique()->nullable();
            $table->bigInteger('withdrawal_method_id')->unsigned()->nullable();
            $table->text('withdrawal_account')->nullable();
            $table->boolean('was_subscribed')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('kyc_status')->default(false);
            $table->boolean('google2fa_status')->default(false)->comment('0: Disabled, 1: Active');
            $table->text('google2fa_secret')->nullable();
            $table->boolean('status')->default(true)->comment('0: Banned, 1: Active');
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('set null');
            $table->foreign("withdrawal_method_id")->references("id")->on('withdrawal_methods')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};

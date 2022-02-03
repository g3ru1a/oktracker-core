<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialBadgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color');
            $table->string('color_accent');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('social_badges_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_badges_id');
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_badges');
        Schema::dropIfExists('social_badges_user');
    }
}

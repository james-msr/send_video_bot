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
        Schema::create('channel_profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->references('id')->on('channels');
            $table->foreignId('insta_profile_id')->references('id')->on('insta_profiles');
            $table->index(['channel_id', 'insta_profile_id']);
            $table->string('last_key')->nullable();
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
        Schema::dropIfExists('channel_profile');
    }
};

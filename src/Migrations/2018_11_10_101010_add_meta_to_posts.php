<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMetaToPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wink_posts_meta', function (Blueprint $table) {
            $table->increments('id');

            $table->uuid('wink_post_id')->index();
            $table->foreign('wink_post_id')->references('id')->on('wink_posts')->onDelete('cascade');

            $table->string('key')->index();
            $table->text('value')->nullable();

            $table->unique(['wink_post_id', 'key']);

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
        Schema::dropIfExists('wink_posts_meta');
    }
}

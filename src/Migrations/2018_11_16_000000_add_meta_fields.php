<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetaFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(config('wink.table_names.wink_tags', 'wink_tags'), function (Blueprint $table) {
            $table->text('meta')->nullable();
        });

        Schema::table(config('wink.table_names.wink_pages', 'wink_pages'), function (Blueprint $table) {
            $table->text('meta')->nullable();
        });

        Schema::table(config('wink.table_names.wink_authors', 'wink_authors'), function (Blueprint $table) {
            $table->text('meta')->nullable();
        });

        Schema::table(config('wink.table_names.wink_posts', 'wink_posts'), function (Blueprint $table) {
            $table->text('meta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(config('wink.table_names.wink_tags', 'wink_tags'), function (Blueprint $table) {
            $table->dropColumn('meta');
        });

        Schema::table(config('wink.table_names.wink_pages', 'wink_pages'), function (Blueprint $table) {
            $table->dropColumn('meta');
        });

        Schema::table(config('wink.table_names.wink_authors', 'wink_authors'), function (Blueprint $table) {
            $table->dropColumn('meta');
        });

        Schema::table(config('wink.table_names.wink_posts', 'wink_posts'), function (Blueprint $table) {
            $table->dropColumn('meta');
        });
    }
}

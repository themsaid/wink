<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMarkdownField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(config('wink.table_names.wink_posts', 'wink_posts'), function (Blueprint $table) {
            $table->boolean('markdown')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(config('wink.table_names.wink_posts', 'wink_posts'), function (Blueprint $table) {
            $table->dropColumn('markdown');
        });
    }
}

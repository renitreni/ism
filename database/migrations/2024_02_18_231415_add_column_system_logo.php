<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSystemLogo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('print_settings', function (Blueprint $table) {
            //
            $table->string('system_logo')->nullable()->after('header_logo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('print_settings', function (Blueprint $table) {
            //
            $table->dropColumn('system_logo');
        });
    }
}

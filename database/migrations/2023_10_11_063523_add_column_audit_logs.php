<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAuditLogs extends Migration
{
    /**
     * Run the migrations.
     * php artisan migrate --path=database/migrations/2023_10_11_063523_add_column_audit_logs
     *  
     * @return void
     */
    public function up()
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string("action_id",100)->nullable();
            $table->string("action",100)->nullable();
            $table->string("current",100)->nullable();
            $table->string("previous",100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_logs', function (Blueprint $table) { 
            $table->dropColumn("action_id",100);
            $table->dropColumn("action",100);
            $table->dropColumn("current",100);
            $table->dropColumn("previous",100);
        });
    }
}

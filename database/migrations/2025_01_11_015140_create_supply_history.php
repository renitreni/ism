<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplyHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply_history', function (Blueprint $table) {
            $table->id();
            $table->integer('supply_id');
            $table->string('supply_name');
            $table->integer('quantity');
            $table->string('unit');
            $table->string('from');
            $table->string('action_by');
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
        Schema::dropIfExists('supply_history');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('cost_center')->nullable();
            $table->text('description')->nullable();
            $table->text('person_assigned')->nullable();
            $table->date('expense_date')->nullable();
            $table->string('si_no')->nullable();
            $table->string('dr_no')->nullable();
            $table->float('total_amount')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('expenses');
    }
}

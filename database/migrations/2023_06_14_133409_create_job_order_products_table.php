<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_order_id')->constrained();
            $table->text('product');
            $table->integer('qty');
            $table->text('serial_number');
            $table->text('physical_appearance');
            $table->text('product_status');
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
        Schema::dropIfExists('job_order_products');
    }
}

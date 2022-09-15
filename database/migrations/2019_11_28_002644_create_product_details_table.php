<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('purchase_order_id')->nullable();
            $table->bigInteger('sales_order_id')->nullable();
            $table->string('product_id',200)->nullable();
            $table->text('product_name')->nullable();
            $table->string('product_code',200)->nullable();
            $table->text('notes')->nullable();
            $table->float('qty')->nullable();
            $table->float('selling_price')->nullable();
            $table->float('labor_cost')->nullable();
            $table->float('vendor_price')->nullable();
            $table->float('discount_item')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('product_details');
    }
}

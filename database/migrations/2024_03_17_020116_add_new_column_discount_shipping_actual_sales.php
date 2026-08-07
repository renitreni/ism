<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnDiscountShippingActualSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_details', function (Blueprint $table) {
            $table->decimal('discount', 8, 2)->default(0)->after('vendor_price')->nullable();
            $table->decimal('shipping', 8, 2)->default(0)->after('vendor_price')->nullable();
            $table->decimal('actual_sales', 8, 2)->default(0)->after('vendor_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_details', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',200)->nullable();
            $table->string('contact_person',200)->nullable();
            $table->string('landline',200)->nullable();
            $table->string('mobile_phone',200)->nullable();
            $table->string('email',200)->nullable();
            $table->string('payment_method',200)->nullable();
            $table->string('shipping_method',200)->nullable();
            $table->text('address')->nullable();
            $table->string('assigned_to',200)->nullable();
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
        Schema::dropIfExists('vendors');
    }
}

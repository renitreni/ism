<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBackupLogsTable extends Migration
{
    public function up()
    {
        Schema::create('backup_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category');
            $table->string('type'); // backup or restore
            $table->string('status'); // success or failed
            $table->string('file_path')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->text('message')->nullable();
            $table->unsignedBigInteger('performed_by')->nullable();
            $table->timestamps();

            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('backup_logs');
    }
}

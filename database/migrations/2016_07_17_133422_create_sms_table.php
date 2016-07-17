<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text')->nullable();
            $table->integer('task_id')->unsigned()->nullable()->index();
            $table->integer('recipient_id')->unsigned()->nullable()->index();
            $table->integer('sender_id')->unsigned()->index();
            $table->string('phone')->nullable()->index();
            $table->boolean('status')->unsigned()->nullable()->default(FALSE);
            $table->string('error_code')->nullable();
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
        Schema::drop('sms');
    }
}

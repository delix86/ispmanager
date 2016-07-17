<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('type_id')->unsigned()->index();
            $table->integer('state_id')->unsigned()->default(1)->index();
            $table->integer('priority_id')->unsigned()->index();
            $table->boolean('viewed')->default(FALSE);
            $table->integer('author_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            //$table->string('task_name', 50);
            $table->text('name')->nullable();
            $table->text('text')->nullable();
            $table->text('result')->nullable();
            $table->integer('uid')->unsigned()->nullable()->index();
            $table->string('login', 30)->nullable()->index();
            $table->string('fio', 50)->nullable();
            $table->string('phone1', 20)->nullable();
            $table->string('phone2', 20)->nullable();
            $table->string('phone3', 20)->nullable();
            $table->string('address', 50)->nullable();
            //$table->string('avatar', 50)->nullable();
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
        Schema::drop('tasks');
    }
}

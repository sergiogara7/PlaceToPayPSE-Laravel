<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',60);
            //$table->string('email',1000)->unique();
            $table->string('email',80);
            $table->string('password');
            $table->string('document',12);
            $table->string('documentType',3);
            $table->string('lastName',60);
            $table->string('company',60);
            $table->string('address',100);
            $table->string('city',50);
            $table->string('province',50);
            $table->string('country',2);
            $table->string('phone',30);
            $table->string('mobile',30);
            $table->rememberToken();
            $table->timestamps();
			$table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
			$table->string('bankCode',4);
			$table->string('bankInterface',1);
			$table->string('ipAddress',15)->nullable();
			$table->string('userAgent',255)->nullable();
			$table->double('totalAmount');
			$table->string('description',255);
			$table->string('bankURL',255)->nullable();
			$table->integer('transactionID')->nullable();
			$table->integer('responseReasonCode')->nullable();
			$table->string('responseReasonText',255)->nullable();
			$table->string('returnCode',30)->nullable();
			$table->string('transactionState',255)->nullable();
			$table->integer('user_id')->unsigned();
            $table->timestamps();
			$table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('transactions');
    }
}

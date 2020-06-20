<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration {

	public function up()
	{
		Schema::create('clients', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->string('name')->unique();
			$table->string('email')->unique();
			$table->string('phone');
			$table->string('image');
			$table->integer('district_id')->unsigned();
			$table->string('password');
			$table->string('pin_code')->unique()->nullable();
			$table->string('api_token')->unique()->nullable();
			$table->rememberToken('rememberToken');
		});
	}

	public function down()
	{
		Schema::drop('clients');
	}
}
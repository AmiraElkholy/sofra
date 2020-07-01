<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRestaurantsTable extends Migration {

	public function up()
	{
		Schema::create('restaurants', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->string('name');
			$table->string('email')->unique();
			$table->integer('delivery_time');
			$table->integer('district_id')->unsigned();
			$table->string('password');
			$table->decimal('minimum_charge');
			$table->decimal('delivery_fees');
			$table->string('phone')->unique();
			$table->string('whatsapp')->unique();
			$table->string('image');
			$table->boolean('is_open')->default(true);
			$table->string('pin_code')->unique()->nullable();
			$table->string('api_token')->unique()->nullable();
			$table->rememberToken('rememberToken');
		});
	}

	public function down()
	{
		Schema::drop('restaurants');
	}
}
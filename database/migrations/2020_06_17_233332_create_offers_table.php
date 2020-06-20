<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOffersTable extends Migration {

	public function up()
	{
		Schema::create('offers', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->string('name');
			$table->text('description');
			$table->string('image');
			$table->datetime('from');
			$table->datetime('to');
			$table->integer('restaurant_id')->unsigned();
			$table->decimal('price');
		});
	}

	public function down()
	{
		Schema::drop('offers');
	}
}
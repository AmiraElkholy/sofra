<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDownpaymentsTable extends Migration {

	public function up()
	{
		Schema::create('downpayments', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->decimal('amount');
			$table->integer('restaurant_id')->unsigned();
			$table->text('notes')->nullable();
			$table->datetime('date');
		});
	}

	public function down()
	{
		Schema::drop('downpayments');
	}
}
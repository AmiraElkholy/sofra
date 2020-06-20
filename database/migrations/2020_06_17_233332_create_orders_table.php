<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	public function up()
	{
		Schema::create('orders', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->text('notes')->nullable();
			$table->string('delivery_address');
			$table->integer('client_id')->unsigned();
			$table->integer('restaurant_id')->unsigned();
			$table->decimal('sub_total', 18,2);
			$table->decimal('delivery_fees', 8,2);
			$table->decimal('total', 20,2);
			$table->integer('payment_method_id')->unsigned();
			$table->enum('state', array('pending', 'accepted', 'rejected', 'delivered', 'declined'));
			$table->text('reason_for_rejection')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('orders');
	}
}
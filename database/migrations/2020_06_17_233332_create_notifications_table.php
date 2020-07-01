<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsTable extends Migration {

	public function up()
	{
		Schema::create('notifications', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->integer('order_id')->unsigned()->nullable();
			$table->string('title');
			$table->text('content');
			$table->integer('notifiable_id')->unsigned();
			$table->string('notifiable_type');
			$table->boolean('is_seen')->default(false);
		});
	}

	public function down()
	{
		Schema::drop('notifications');
	}
}
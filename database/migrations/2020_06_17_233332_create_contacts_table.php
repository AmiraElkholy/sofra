<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactsTable extends Migration {

	public function up()
	{
		Schema::create('contacts', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->string('fullname');
			$table->string('email');
			$table->string('phone');
			$table->text('message');
			$table->enum('type', array('complaint', 'suggestion', 'inquiry'));
			$table->integer('contactable_id')->unsigned();
			$table->string('contactable_type');
		});
	}

	public function down()
	{
		Schema::drop('contacts');
	}
}
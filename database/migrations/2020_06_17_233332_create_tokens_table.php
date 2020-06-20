<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTokensTable extends Migration {

	public function up()
	{
		Schema::create('tokens', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->string('notification_token');
			$table->integer('tokenable_id')->unsigned();
			$table->string('tokenable_type');
			$table->enum('platform', array('android', 'ios'));
		});
	}

	public function down()
	{
		Schema::drop('tokens');
	}
}
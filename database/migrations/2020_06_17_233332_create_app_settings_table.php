<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAppSettingsTable extends Migration {

	public function up()
	{
		Schema::create('app_settings', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->text('about_us_text');
			$table->text('commissions_page_text');
			$table->double('commissions_rate');
			$table->text('payment_account_details');
		});
	}

	public function down()
	{
		Schema::drop('app_settings');
	}
}
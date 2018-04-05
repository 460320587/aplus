<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudiosTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('audios', function(Blueprint $table) {
            $table->increments('audio_id');
            $table->unsignedInteger('user_id')->index();

            // Adding more table related fields here...
            $table->unsignedInteger('album_id')->index();
            $table->string('name')->nullable();
            $table->string('original_name')->nullable();
            $table->unsignedInteger('someline_file_id')->index()->nullable();
            $table->string('audio_length', 20)->nullable();
            $table->smallInteger('sequence')->nullable();
            $table->smallInteger('status')->default(0);

            $table->softDeletes();

            $table->unsignedInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->ipAddress('created_ip')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->ipAddress('updated_ip')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('audios');
	}

}

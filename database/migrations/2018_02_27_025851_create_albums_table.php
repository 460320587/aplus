<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->increments('album_id');
            $table->unsignedInteger('user_id')->index();

            // Adding more table related fields here...
            $table->unsignedInteger('related_user_id')->index()->nullable();
            $table->string('book_id', 50);
            $table->string('name', 150)->nullable();
            $table->string('author', 150)->nullable();
            $table->string('broadcaster', 150)->nullable();
            $table->string('broadcaster_type', 150)->nullable();
            $table->unsignedInteger('someline_image_id')->index()->nullable();
            $table->text('brief')->nullable();
            $table->smallInteger('payment_type')->nullable();
            $table->decimal('payment_percentage', 5, 2)->nullable();
            $table->decimal('payment_amount', 8, 2)->nullable();
            $table->string('keywords', 150)->nullable();
            $table->string('copyright', 150)->nullable();
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
        Schema::drop('albums');
    }

}

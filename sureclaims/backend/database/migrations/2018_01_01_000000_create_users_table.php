<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('auth0_id', 255)
                ->nullable();
            $table->integer('customer_id')
                ->unsigned()
                ->nullable();
            $table->string('email', 191)->unique();
            $table->boolean('email_verified')
                ->default(false);
            $table->string('name', 255)
                ->nullable();
            $table->string('nickname', 255)
                ->nullable();
            $table->string('last_login', 255)
                ->nullable();
            $table->string('last_ip', 255)
                ->nullable();
            $table->text('app_metadata')
                ->nullable();
            $table->text('user_metadata')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

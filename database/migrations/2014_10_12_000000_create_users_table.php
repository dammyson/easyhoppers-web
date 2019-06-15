<?php

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
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('unique_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('terminal')->nullable();
            $table->string('subscription')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('occupation')->nullable();
            $table->string('DOB')->nullable();
            $table->string('gender')->nullable();
            $table->decimal('balance',8,2)->default('0');
            $table->tinyInteger('status')->default('0');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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

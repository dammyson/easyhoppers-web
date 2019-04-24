<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('airlineCode');
            $table->string('schedule_name');
            $table->time('scheduled_departure_time');
            $table->time('scheduled_arrival_time');
            $table->time('actual_departure_time')->nullable();
            $table->integer('route_id');
            $table->decimal('amount',10,2);
            $table->date('scheduled_departure_date');
            $table->date('actual_departure_date')->nullable();
            $table->date('scheduled_arrival_date');
            $table->date('actual_arrival_date')->nullable();
            $table->string('description');
            $table->integer('status');
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
        Schema::dropIfExists('schedules');
    }
}

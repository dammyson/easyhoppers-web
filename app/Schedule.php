<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'schedule_name', 'airlineCode', 'scheduled_departure_time', 'scheduled_arrival_time','route_id','amount','scheduled_departure_date','scheduled_arrival_date'
    ];

}

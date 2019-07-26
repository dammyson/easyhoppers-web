<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'departure_port', 'arrival_port', 'departure_port_name', 'arrival_port_name','code'
    ];
}

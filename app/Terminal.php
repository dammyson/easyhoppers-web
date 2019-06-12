<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'state_id','description'
    ];
}

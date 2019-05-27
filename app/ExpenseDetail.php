<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category', 'amount', 'description', 'expense_id'
    ];
}

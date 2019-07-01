<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfoTransaction extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total_transactions', 'date',
    ];

    /**
     * Hide the original columns
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

}
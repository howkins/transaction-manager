<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'type', 'amount', 'account_id'
    ];
}

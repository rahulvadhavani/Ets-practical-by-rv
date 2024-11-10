<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $guarded = ['id'];

    public static function  getStates()
    {
        return self::select('id', 'name')->get();
        
    }
}

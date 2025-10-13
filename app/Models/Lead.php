<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['name','phone','message','status'];

    protected $casts = [
        'status' => 'string',
    ];
}

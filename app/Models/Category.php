<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','slug','position','is_active'];
    protected $casts = ['is_active' => 'bool'];


}



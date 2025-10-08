<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Slider extends Model
{

    protected $fillable = ['title','subtitle','link','position','is_active'];
    protected $casts = ['is_active' => 'bool'];


}

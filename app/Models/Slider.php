<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Slider extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['title','subtitle','link','position','is_active'];
    protected $casts = ['is_active' => 'bool'];

    public function registerMediaCollections(): void {
        $this->addMediaCollection('image');
    }
    public function registerMediaConversions(?Media $media = null): void {
        $this->addMediaConversion('webp')->format('webp')->quality(85)->nonQueued();
    }


}

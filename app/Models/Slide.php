<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Slide extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'slider_id','title','subtitle','button_text','button_link','position','is_active'
    ];
    protected $casts = ['is_active'=>'bool'];

    public function slider() { return $this->belongsTo(Slider::class); }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('slide_image');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('webp')
            ->format('webp')
            ->nonQueued()
            ->quality(85)
            ->performOnCollections('slide_image');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('slide_image','webp') ?: $this->getFirstMediaUrl('slide_image');
    }
}

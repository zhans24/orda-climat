<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Page extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title', 'slug', 'template', 'is_published',
        'meta_title', 'meta_description', 'content',
    ];

    protected $casts = [
        'is_published' => 'bool',
        'content' => 'array',
    ];

    public function section(string $key, $default = null)
    {
        return data_get($this->content, $key, $default);
    }


    public function registerMediaCollections(): void
    {
        // О компании:
        $this->addMediaCollection('about_image')->singleFile();
        $this->addMediaCollection('about_clients');
        $this->addMediaCollection('about_certificates');

        $this->addMediaCollection('insta_images');     // до 4 штук, порядок важен
        $this->addMediaCollection('services_images');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('webp')
            ->format('webp')
            ->quality(85)
            ->nonQueued();
    }

    // Удобный аксессор для about_image
    public function getAboutImageUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('about_image', 'webp')
            ?: $this->getFirstMediaUrl('about_image');
    }
}

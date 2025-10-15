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
        $this->addMediaCollection('about_image')->singleFile();
        $this->addMediaCollection('about_clients');
        $this->addMediaCollection('about_certificates');

        $this->addMediaCollection('insta_images');
        $this->addMediaCollection('services_images');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('webp')
            ->format('webp')
            ->quality(85)
            ->nonQueued();
    }

    public function getAboutImageUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('about_image', 'webp')
            ?: $this->getFirstMediaUrl('about_image');
    }

    public static function getContentValue(string $template, string $dotPath, $fallback = null)
    {
        /** @var self|null $page */
        $page = static::query()
            ->where('template', $template)
            ->where('is_published', true)
            ->first();

        $val = data_get($page?->content, $dotPath);
        return $val !== null ? $val : $fallback;
    }

    public static function cartDeliveryPriceGlobal(int $fallback = 5000): int
    {
        $val = static::getContentValue('cart', 'delivery_flat_price', $fallback);
        return is_numeric($val) ? (int) $val : $fallback;
    }

    public static function cartShowDeliveryOption(bool $fallback = true): bool
    {
        return (bool) static::getContentValue('cart', 'show_delivery_option', $fallback);
    }
}

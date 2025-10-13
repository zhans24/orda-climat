<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'category_id','name','slug','sku','price',
        'is_active','is_available','short','description',
        'meta_title','meta_description','attributes',
    ];

    protected $casts = [
        'is_active'   => 'bool',
        'is_featured' => 'bool',
        'price'       => 'integer',
        'attributes'  => 'array',
    ];

    public function category(): BelongsTo { return $this->belongsTo(Category::class); }

    // Scopes
    public function scopeActive(Builder $q): Builder { return $q->where('is_active', true); }
    public function scopeInCategoryTree(Builder $q, Category $cat): Builder
    {
        $ids = Category::descendantsAndSelf($cat->id)->pluck('id');
        return $q->whereIn('category_id', $ids);
    }

    // SEO
    public function getSeoTitleAttribute(): string { return $this->meta_title ?: $this->name; }
    public function getSeoDescriptionAttribute(): string
    {
        $base = $this->meta_description ?: ($this->short ?: strip_tags((string) $this->description));
        return (string) str($base)->limit(160);
    }

    // Media
    public function registerMediaCollections(): void { $this->addMediaCollection('cover')->singleFile(); }
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->format('webp')->fit(Fit::Crop, 400, 300)->nonQueued();
        $this->addMediaConversion('card')->format('webp')->fit(Fit::Contain, 800, 600)->nonQueued();
        $this->addMediaConversion('xl')->format('webp')->fit(Fit::Contain, 1600, 1200)->nonQueued();
    }
    public function getImageUrlAttribute(): ?string
    {
        // сначала пытаемся отдать конверсию 'card' из коллекции 'cover'
        $url = $this->getFirstMediaUrl('cover', 'card');
        if (!empty($url)) {
            return $url;
        }

        // если конверсия не создана, отдаем оригинал из 'cover'
        $url = $this->getFirstMediaUrl('cover');
        return $url ?: null;
    }
}

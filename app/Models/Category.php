<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;

class Category extends Model implements HasMedia
{
    use NodeTrait;
    use InteractsWithMedia;

    protected $fillable = [
        'parent_id','name','slug','is_active','is_popular','position',
        'meta_title','meta_description','description',
    ];

    // Relations
    public function parent(): BelongsTo   { return $this->belongsTo(Category::class, 'parent_id'); }
    public function children(): HasMany   { return $this->hasMany(Category::class, 'parent_id')->orderBy('position'); }
    public function products(): HasMany   { return $this->hasMany(Product::class); }

    // Scopes
    public function scopeActive(Builder $q): Builder  { return $q->where('is_active', true); }
    public function scopeRoots(Builder $q): Builder   { return $q->whereIsRoot(); }
    public function scopeLeaves(Builder $q): Builder  { return $q->whereIsLeaf(); }
    public function scopeOrdered(Builder $q): Builder { return $q->orderBy('position'); }

    // SEO
    public function getSeoTitleAttribute(): string { return $this->meta_title ?: $this->name; }
    public function getSeoDescriptionAttribute(): string
    {
        $base = $this->meta_description ?: ($this->description ? strip_tags($this->description) : $this->name);
        return (string) str($base)->limit(160);
    }

    // Media
    public function registerMediaCollections(): void { $this->addMediaCollection('cover')->singleFile(); }
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->format('webp')->fit(Fit::Crop, 400, 300)->quality(85)->nonQueued()
            ->performOnCollections('cover');
        $this->addMediaConversion('card')->format('webp')->fit(Fit::Contain, 800, 600)->quality(85)->nonQueued()
            ->performOnCollections('cover');
        $this->addMediaConversion('xl')->format('webp')->fit(Fit::Contain, 1600, 1200)->quality(85)->nonQueued()
            ->performOnCollections('cover');
    }
    public function getImageUrlAttribute(): ?string
    {
        $url = $this->getFirstMediaUrl('cover', 'card');
        if (!empty($url)) {
            return $url;
        }

        $url = $this->getFirstMediaUrl('cover');
        return $url ?: null;
    }


    public function descendantsAndSelfIds(): Collection
    {
        return Category::descendantsAndSelf($this->id)->pluck('id');
    }

    public function breadcrumbs(): Collection
    {
        return Category::ancestorsAndSelf($this->id)
            ->sortBy('_lft')
            ->values();
    }
}

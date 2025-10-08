<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'category_id','name','slug','code','price','is_active','description','attributes',
    ];

    protected $casts = [
        'is_active' => 'bool',
        'price'     => 'decimal:2',
        'attributes'=> 'array',
    ];

    public function category() { return $this->belongsTo(Category::class); }
    public function section()  { return $this->belongsTo(Section::class); }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('webp')
            ->format('webp')
            ->quality(85)
            ->performOnCollections('images')
            ->nonQueued();
    }

}


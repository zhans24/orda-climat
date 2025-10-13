<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSettings extends Model
{
    protected $fillable = [
        'company_name', 'tagline',
        'phone', 'whatsapp', 'instagram', 'tiktok','email',
        'address', 'map_iframe',
    ];


    public static function singleton(): self
    {
        return static::query()->firstOrCreate([]);
    }
}

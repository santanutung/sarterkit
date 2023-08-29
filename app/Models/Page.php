<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Page extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $guarded = ['id'];


    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
        ->singleFile();
    }

    static function findBySlug ($slug){
       return  self::where('slug', $slug)->where('status', true)->firstOrFail();
    }
}


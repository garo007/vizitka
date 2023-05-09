<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class News extends Model
{
    use HasFactory;
    protected $appends = ['image'];
    protected $guarded = ['id'];

    public function getImageAttribute()
    {
        return $this->image()->first() ? $this->image()->first()->fullPath : null;
    }

    /**
     * @return MorphOne
     */
    public function image(): MorphOne
    {
        return $this->morphOne(File::class, 'model')->where('type', 'image');
    }

    public function files(): MorphOne
    {
        return $this->morphOne(File::class, 'model');
    }
}

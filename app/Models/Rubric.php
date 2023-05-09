<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Rubric extends Model
{
    use HasFactory;

    protected $appends = ['icon'];

    protected $guarded = ['id'];

    public function getIconAttribute()
    {
        return $this->icon()->first() ? $this->icon()->first()->fullPath : null;
    }

    /**
     * @return MorphOne
     */
    public function icon(): MorphOne
    {
        return $this->morphOne(File::class, 'model')->where('type', 'icon');
    }
}

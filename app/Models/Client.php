<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;

class Client extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions;

    protected $guard = 'client';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'join_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     */
    protected $appends = ['images', 'logo', 'tag_ids'];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'client_tag');
    }

    /**
     * @return MorphOne
     */
    public function files(): MorphOne
    {
        return $this->morphOne(File::class, 'model');
    }

    /**
     * @return MorphOne
     */
    public function logo(): MorphOne
    {
        return $this->morphOne(File::class, 'model')->where('type', 'logo');
    }

    /**
     * @return MorphOne
     */
    public function images(): MorphOne
    {
        return $this->morphOne(File::class, 'model')->where(['type' => 'image']);
    }

    public function getLogoAttribute()
    {
        return $this->logo()->first() ? $this->logo()->first()->fullPath : null;
    }

    public function getTagIdsAttribute()
    {
        return $this->tags()->get()->pluck('id');
    }

    public function getImagesAttribute()
    {
        $images = [];
        foreach ($this->images()->get() as $image) {
            $images[] = $image->fullPath;
        }
        return $images;
    }

    /**
     * Hash the password on write.
     *
     * @param string $password
     */
    public function setPasswordAttribute(string $password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function scopeFilter($builder, $filters = [])
    {
        $filters = json_decode($filters);
        if (!$filters) {
            return $builder;
        }

        if ($filters->rubric_id) {
            $builder->where('rubric_id', $filters->rubric_id);
        }

        return $builder;
    }
}

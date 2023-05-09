<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AirTicketSliceDetail extends Model
{
    use HasFactory;

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
        'type'
    ];

    public function airports()
    {
        return $this->hasMany(AirTicketSliceDetailAirport::class, 'slice_detail_id');
    }

    public function airTicketSlice(): BelongsTo
    {
        return $this->belongsTo(AirTicketSlice::class);
    }

    /**
     * The accessors to append to the model's array form.
     *
     */
    protected $appends = ['airports'];

    public function getAirportsAttribute(): object
    {
        return $this->airports()->get();
    }
}

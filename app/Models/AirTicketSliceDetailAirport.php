<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirTicketSliceDetailAirport extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = ['id'];

    public function city()
    {
        return $this->hasOne(City::class, 'slice_detail_airport_id');
    }

    public function airTicketSliceDetail()
    {
        return $this->belongsTo(AirTicketSliceDetail::class, 'slice_detail_id');
    }

    /**
     * The accessors to append to the model's array form.
     *
     */
    protected $appends = ['city'];

    public function getCityAttribute()
    {
        return $this->city()->first();
    }
}

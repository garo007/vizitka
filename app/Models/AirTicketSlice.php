<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirTicketSlice extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = ['id'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['origin', 'destination'];

    public function origin()
    {
        return $this->hasOne(AirTicketSliceDetail::class)->where(['type' => 'origin']);
    }

    public function destination()
    {
        return $this->hasOne(AirTicketSliceDetail::class)->where(['type' => 'destination']);
    }
}

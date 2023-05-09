<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Support\Str;

class AirTicket extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'live_mode' => 'boolean',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['slices'];

    public function slices(): hasMany
    {
        return $this->hasMany(AirTicketSlice::class);
    }

    public static function generateAirTicketId(): string
    {
        do {
            $token = 'atk_' . Str::random(15);
        } while (AirTicket::where('air_ticket_id', $token)->exists());
        return $token;
    }
}

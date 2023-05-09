<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = ['id'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['info'];

    /**
     * @return HasOne
     */
    public function model(): HasOne
    {
        return $this->hasOne($this->model_type, 'id', 'model_id');
    }

    /**
     * @return HasOne
     */
    public function type(): HasOne
    {
        return $this->hasOne(NotificationType::class, 'id', 'notification_type_id');
    }

    public function getInfoAttribute()
    {
        $type = $this->type()->first();

        return [
            'id' => $this['id'],
            'type' => $type['name'],
            'is_read' => (boolean)$this['is_read'],
            'created_at' => $this['created_at']
        ];
    }
}

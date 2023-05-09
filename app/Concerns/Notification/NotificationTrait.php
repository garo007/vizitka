<?php

namespace App\Concerns\Notification;

use App\Models\Notification;
use App\Models\NotificationType;
use App\Events\NotificationEvent;
use Illuminate\Validation\ValidationException;

trait NotificationTrait
{
    use ValidateNotification;

    public function notify($model, $name, $settings)
    {
        $notificationType = NotificationType::query()->where('name', $name)->first();

        try {
            $this->validate($settings, $notificationType['name']);

            $notification = Notification::query()->create([
                'model_id' => $model['id'],
                'model_type' => get_class($model),
                'notification_type_id' => $notificationType['id'],
                'settings' => json_encode($settings)
            ]);

            broadcast(new NotificationEvent($notification));
            return true;
        } catch (ValidationException) {
            return false;
        }
    }
}

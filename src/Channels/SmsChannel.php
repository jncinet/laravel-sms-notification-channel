<?php

namespace Jncinet\Notifications\Channels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    /**
     * 发送短信通知。
     *
     * @param $notifiable
     * @param Notification $notification
     */
    public function send($notifiable, Notification $notification)
    {
        if ($notifiable instanceof Model) {
            $mobile = $notifiable->routeNotificationFor('Sms');
        } elseif ($notifiable instanceof AnonymousNotifiable) {
            if (isset($notifiable->routes['easy-sms'])) {
                $mobile = $notifiable->routes['easy-sms'];
            } elseif (isset($notifiable->routes[__CLASS__])) {
                $mobile = $notifiable->routes[__CLASS__];
            } else {
                return;
            }
        } else {
            return;
        }

        if (!preg_match("/^1[345789]\d{9}$/", $mobile)) {
            return;
        }

        $message = $notification->toEasySms($notifiable);

        app('easy-sms')->send($mobile, $message);
    }
}
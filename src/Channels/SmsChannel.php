<?php

namespace Jncinet\Notifications\Channels;

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
        $mobile = $notifiable->routeNotificationFor('Sms');

        if (!preg_match("/^1[345789]\d{9}$/", $mobile)) {
            return;
        }

        $message = $notification->toSms($notifiable);

        app('easy-sms')->send($mobile, $message);
    }
}
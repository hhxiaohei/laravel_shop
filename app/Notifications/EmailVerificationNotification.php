<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

//implements ShouldQueue 将会把该任务放到异步队列中去
class EmailVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $token = str_random(16);
        Cache::set('email_verification_' . $notifiable->email, $token, 30);
        return (new MailMessage)
            ->greeting($notifiable->name . ' 您好: ')//邮件欢迎词
            ->subject('注册成功 请验证邮箱')//邮件标题
            ->line('请点击下方链接验证你的邮箱')//添加一行字
            ->action('验证', route('email_verification.verify', [
                'email' => $notifiable->email,
                'token' => $token,
            ]));//激活按钮
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

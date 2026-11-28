<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordId extends ResetPassword
{
    /** Versi Bahasa Indonesia dari email reset password */
    public function toMail($notifiable): MailMessage
    {
        $url = $this->resetUrl($notifiable);
        $expire = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire', 60);

        return (new MailMessage)
            ->subject('Reset Password — Dolan Semarang')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Kami menerima permintaan reset password untuk akun Dolan Semarang-mu.')
            ->action('Reset Password', $url)
            ->line("Tautan ini hanya berlaku {$expire} menit dan bisa dipakai sekali.")
            ->line('Kalau kamu tidak meminta reset ini, abaikan email ini — password-mu tidak akan berubah.')
            ->salutation('Salam hangat, Tim Dolan Semarang');
    }
}

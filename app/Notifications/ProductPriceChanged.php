<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductPriceChanged extends Notification
{
    use Queueable;

    public $product;
    public $oldPrice;
    public $newPrice;

    /**
     * Create a new notification instance.
     */
    public function __construct($product, $oldPrice, $newPrice)
    {
        $this->product = $product;
        $this->oldPrice = $oldPrice;
        $this->newPrice = $newPrice;
    }

    /**
     * The notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database']; // hanya dashboard, tanpa email/wa
    }

    /**
     * Get the array representation of the notification (for database storage).
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Harga Produk Berubah',
            'message' => "Harga produk '{$this->product}' berubah dari Rp" . number_format($this->oldPrice, 0, ',', '.') .
                " menjadi Rp" . number_format($this->newPrice, 0, ',', '.'),
        ];
    }
}

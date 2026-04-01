<?php

namespace App\Notifications;

use App\Models\TravelOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChanged extends Notification
{
    use Queueable;

    // O "public" aqui é obrigatório para o $this->order funcionar nos outros métodos
    public function __construct(public TravelOrder $order)
    {
        //
    }

    public function via($notifiable): array
    {
        return ['database']; // Canal de log para o teste rápido
    }

    // Se usar o canal 'database', o Laravel procura o método toArray:
    public function toArray($notifiable): array
    {
        return [
            'message' => "O pedido #{$this->order->id} foi {$this->order->status->value}.",
            'order_id' => $this->order->id
        ];
    }
}

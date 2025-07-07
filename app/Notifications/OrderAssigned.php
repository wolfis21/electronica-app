<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;

// Añadimos 'ShouldBroadcast' para que se envíe en tiempo real.
class OrderAssigned extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Define los canales por los que se enviará la notificación.
     * 'database': La guarda en la tabla 'notifications'.
     * 'broadcast': La envía al servidor de WebSockets.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Define los datos que se guardarán en la base de datos
     * y se enviarán al frontend.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'message' => "Se te ha asignado la orden #{$this->order->id}: {$this->order->name_equip}",
            'url' => route('orders.show', $this->order->id), // URL para que el usuario haga clic
        ];
    }
}
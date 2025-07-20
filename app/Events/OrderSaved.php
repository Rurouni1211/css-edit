<?php

namespace App\Events;

use App\Enums\OrderStatus;
use App\Models\Admin;
use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdated;

class OrderSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order)
    {
        // もしステータスが変更されていたら、履歴を保存する
        if ($order->isDirty('status')) {

            $status_from = $order->getOriginal('status', OrderStatus::None->value);
            $status_to = $order->status;
            $user = null;
            $multi_auth_user_id = null;

            if(app()->runningInConsole()) {

                $user = Admin::first();

            } else {

                $user_type = multi_auth_guard();

                if(! is_null($user_type)) {

                    $user = auth($user_type)->user();

                }

            }

            if(!is_null($user)) {

                $multi_auth_user_id = $user->multi_auth_user->id;

            }

            $order->status_histories()->create([
                'multi_auth_user_id' => $multi_auth_user_id,
                'status_from' => $status_from,
                'status_to' => $status_to,
            ]);
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}

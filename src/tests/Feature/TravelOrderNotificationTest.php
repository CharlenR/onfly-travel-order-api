<?php

namespace Tests\Feature;

use App\Models\TravelOrder;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderStatusChanged;
use Tests\TestCase;

class TravelOrderNotificationTest extends TestCase
{

    public function test_user_is_notified_when_order_is_approved()
    {
        Notification::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        $order = TravelOrder::factory()->create();

        $this->actingAs($admin)
            ->patchJson("/api/travel-orders/{$order->id}/approve")
            ->assertStatus(200);

        Notification::assertSentTo(
            $order->user,
            OrderStatusChanged::class
        );
    }

    public function test_user_is_notified_when_order_is_canceled()
    {
        Notification::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        $order = TravelOrder::factory()->create();

        $this->actingAs($admin)
            ->patchJson("/api/travel-orders/{$order->id}/cancel")
            ->assertStatus(200);

        Notification::assertSentTo(
            $order->user,
            OrderStatusChanged::class
        );
    }
}

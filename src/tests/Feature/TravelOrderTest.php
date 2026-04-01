<?php

namespace Tests\Feature;

use App\Enums\TravelOrderStatus;
use App\Models\User;
use App\Models\TravelOrder;
use App\Notifications\OrderStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_view_another_users_travel_order(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $order = TravelOrder::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)
            ->getJson("/api/travel-orders/{$order->id}");

        $response->assertStatus(403);
    }

    public function test_only_admin_can_approve_travel_order(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $admin = User::factory()->create(['is_admin' => true]);

        $order = TravelOrder::factory()->create(['user_id' => $user->id, 'status' => 'requested']);

        $response1 = $this->actingAs($user)
            ->patchJson("/api/travel-orders/{$order->id}/approve");

        $response1->assertStatus(403);

        $response2 = $this->actingAs($admin)
            ->patchJson(
                "/api/travel-orders/{$order->id}/approve"
            );

        $response2->assertStatus(200);

        $this->assertDatabaseHas('travel_orders', [
            'id' => $order->id,
            'status' => TravelOrderStatus::APPROVED
        ]);
    }
}

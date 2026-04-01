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
    // 1. IMPORTANTE: Limpa o banco de dados a cada teste para não poluir
    use RefreshDatabase;

    public function test_user_cannot_view_another_users_travel_order(): void
    {
        // 2. Criamos dois usuários distintos
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // 3. Criamos um pedido que pertence ao Usuário 1
        $order = TravelOrder::factory()->create(['user_id' => $user1->id]);

        // 4. Tentamos acessar esse pedido LOGADOS como Usuário 2
        // O actingAs simula o login (Sanctum)
        $response = $this->actingAs($user2)
            ->getJson("/api/travel-orders/{$order->id}");

        // 5. O resultado DEVE ser 403 Forbidden (Proibido)
        $response->assertStatus(403);
    }

    public function test_only_admin_can_approve_travel_order(): void
    {
        // 1. Setup: Criamos um usuário comum, um admin e um pedido solicitado
        $user = User::factory()->create(['is_admin' => false]);
        $admin = User::factory()->create(['is_admin' => true]);

        $order = TravelOrder::factory()->create(['user_id' => $user->id, 'status' => 'requested']);

        // 2. TENTATIVA 1: Usuário comum tenta aprovar (Deve falhar)
        $response1 = $this->actingAs($user)
            ->patchJson("/api/travel-orders/{$order->id}/approve");

        $response1->assertStatus(403); // Proibido

        // dump('User ID is: ', $order->user_id, 'Admin is: ', $admin->id, 'User is: ', $user->id);

        // 3. TENTATIVA 2: Administrador tenta aprovar (Deve funcionar)
        $response2 = $this->actingAs($admin)
            ->patchJson(
                "/api/travel-orders/{$order->id}/approve"
            );


        $response2->assertStatus(200); // OK

        // 4. Verificação no Banco: O status mudou mesmo?
        $this->assertDatabaseHas('travel_orders', [
            'id' => $order->id,
            'status' => TravelOrderStatus::APPROVED
        ]);
    }

    public function test_authorization_errors_return_json_response(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $order = TravelOrder::factory()->create(['user_id' => $user->id, 'status' => 'requested']);

        $response = $this->actingAs($user)
            ->patchJson("/api/travel-orders/{$order->id}/approve");

        $response->assertStatus(403)
                 ->assertJsonStructure(['message'])
                 ->assertJson([
                     'message' => 'Acesso negado. Você não tem permissão para realizar esta ação.'
                 ]);
    }
}

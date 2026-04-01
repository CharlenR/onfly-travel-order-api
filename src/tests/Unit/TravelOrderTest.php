<?php

namespace Tests\Unit;

use App\Models\TravelOrder;
use App\Enums\TravelOrderStatus;
use App\Domain\TravelOrder\Exceptions\TravelOrderAlreadyApprovedException;
use App\Domain\TravelOrder\Exceptions\TravelOrderAlreadyCancelledException;

use PHPUnit\Framework\TestCase;

class TravelOrderTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_cannot_approve_already_approved_order()
    {
        $order = new TravelOrder([
            'status' => TravelOrderStatus::APPROVED
        ]);

        $this->expectException(TravelOrderAlreadyApprovedException::class);

        $order->approve();
    }

    public function test_can_approve_pending_order()
    {
        $order = new TravelOrder([
            'status' => TravelOrderStatus::REQUESTED
        ]);

        $order->approve();

        $this->assertEquals(
            TravelOrderStatus::APPROVED,
            $order->status
        );
    }

    public function test_cannot_cancel_already_cancelled_order()
    {
        $order = new TravelOrder([
            'status' => TravelOrderStatus::CANCELED
        ]);

        $this->expectException(TravelOrderAlreadyCancelledException::class);

        $order->cancel();
    }
}

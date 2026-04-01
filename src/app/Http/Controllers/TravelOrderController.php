<?php

namespace App\Http\Controllers;

use App\Events\TravelOrderApproved;
use App\Models\TravelOrder;
use App\Http\Resources\TravelOrderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TravelOrderController extends Controller
{
    /**
     * Display a listing of the TravelOrder.
     */
    public function index(Request $request)
    {
        $query = $request->user()->travelOrders(); // Garante que o usuário só veja os DELE

        // Filtros dinâmicos (Padrão Sênior: usando when)
        $query->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->destination, fn($q) => $q->where('destination', 'like', "%{$request->destination}%"))
            ->when($request->start_date, fn($q) => $q->whereDate('departure_date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->whereDate('return_date', '<=', $request->end_date));

        return TravelOrderResource::collection($query->get());
    }

    /**
     * Store a newly created TravelOrder in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'requester_name' => 'required|string',
            'destination' => 'required|string',
            'departure_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:departure_date',
        ]);

        $order = $request->user()->travelOrders()->create($data);
        return new TravelOrderResource($order);
    }

    /**
     * Display the TravelOrder resource.
     */
    public function show(TravelOrder $travelOrder)
    {
        Gate::authorize('view', $travelOrder);  // Se não for o dono, o Laravel lança 403 automaticamente

        return new TravelOrderResource($travelOrder);
    }

    /**
     * Approve the specified TravelOrder in storage.
     */
    public function approve(TravelOrder $travelOrder)
    {

        Gate::authorize('approve', $travelOrder);

        $travelOrder->approve();

        $travelOrder->save();

        event(new TravelOrderApproved($travelOrder));

        return response()->json([
            'message' => "Travel order approved successfully!",
            'data' => new TravelOrderResource($travelOrder)
        ]);
    }
    /**
     * Cancel the specified TravelOrder in storage.
     */
    public function cancel(TravelOrder $travelOrder)
    {

        Gate::authorize('cancel', $travelOrder);

        $travelOrder->cancel();

        $travelOrder->save();

        event(new TravelOrderApproved($travelOrder));

        return response()->json([
            'message' => "Travel order cancelled successfully!",
            'data' => new TravelOrderResource($travelOrder)
        ]);
    }
}

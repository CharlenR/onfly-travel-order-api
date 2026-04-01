<?php

namespace App\Http\Controllers;

use App\Events\TravelOrderApproved;
use App\Models\TravelOrder;
use App\Http\Resources\TravelOrderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TravelOrderController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'status' => 'nullable|string|max:50|in:requested,approved,canceled',
            'destination' => 'nullable|string|max:255',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        $query = $request->user()->travelOrders();

        $query->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->destination, fn($q) => $q->where('destination', 'like', "%{$request->destination}%"))
            ->when($request->start_date, fn($q) => $q->whereDate('departure_date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->whereDate('return_date', '<=', $request->end_date));

        return TravelOrderResource::collection($query->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'requester_name' => 'required|string|max:255|min:2',
            'destination' => 'required|string|max:255|min:2',
            'departure_date' => 'required|date_format:Y-m-d|after_or_equal:today|before_or_equal:+5 years',
            'return_date' => 'required|date_format:Y-m-d|after:departure_date|before_or_equal:+5 years',
        ]);

        $order = $request->user()->travelOrders()->create($data);
        return new TravelOrderResource($order);
    }

    public function show(TravelOrder $travelOrder)
    {
        Gate::authorize('view', $travelOrder);

        return new TravelOrderResource($travelOrder);
    }

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

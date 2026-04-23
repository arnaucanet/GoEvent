<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'upcoming');
        $now = now();

        $query = Order::with(['event.category'])
            ->where('user_id', Auth::id());

        if ($type === 'upcoming') {
            $query->whereHas('event', function ($eventQuery) use ($now) {
                $eventQuery->where('start_date', '>=', $now);
            });
        }

        if ($type === 'past') {
            $query->whereHas('event', function ($eventQuery) use ($now) {
                $eventQuery->where('start_date', '<', $now);
            });
        }

        $orders = $query->orderByDesc('created_at')->get();

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'tickets' => 'required|array',
            'extras' => 'nullable|array',
            'insurance' => 'boolean',
            'email' => 'required|email',
            'total_price' => 'required|numeric|min:0',
        ]);

        $order = Order::create([
            ...$data,
            'user_id' => Auth::id(),
            'status' => 'confirmed',
        ]);

        $order->load('event.category');

        return response()->json($order, 201);
    }
}

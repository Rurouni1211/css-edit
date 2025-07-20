<?php

namespace App\Http\Controllers\Auth;

use App\Enums\OrderType;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStatusRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderBaseController extends Controller
{
    protected $guard;

    public function index(Request $request)
    {
        return Inertia::render('Auth/Order/Index', [
            'guard' => $this->guard,
            'order_unique_id' => $request->order_unique_id,
            'orderTypes' => OrderType::getCollection(),
        ]);
    }

    public function list(Request $request)
    {
        $orders = Order::query()
            ->with('components', 'shop', 'item')
            ->whereSearch($request)
            ->latest()
            ->paginate(15);

        return OrderResource::collection($orders);
    }

    public function updateStatus(OrderStatusRequest $request, Order $order)
    {
        $order->status = $request->status;
        $result = $order->save();

        return [
            'result' => $result,
        ];
    }
}

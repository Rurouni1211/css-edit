<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display the order completion page.
     *
     * @param  string  $order
     * @return \Inertia\Response
     */
    public function completed($order)
    {
        // Find the order by unique ID
        $orderModel = Order::where('order_unique_id', $order)
            ->with(['components.material', 'components.color'])
            ->firstOrFail();

        return Inertia::render('Store/Order/Completed', [
            'order' => $orderModel,
        ]);
    }
}
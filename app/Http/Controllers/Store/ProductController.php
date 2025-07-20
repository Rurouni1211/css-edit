<?php

namespace App\Http\Controllers\Store;

use App\Enums\MainMaterialCombinationGroup;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;
use App\Models\Material;
use App\Models\MaterialColor;
use App\Models\Order;
use App\Models\OrderComponent;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Store/Product/Index');
    }

    public function show(Product $product)
    {
        $product->load([
            'components.materials.colors.color',
            'components.materials.material.specular_map',
        ]);
        $material_combination_groups = MainMaterialCombinationGroup::getCollection();

        ProductResource::withoutWrapping();

        return Inertia::render('Store/Product/Show', [
            'product' => ProductResource::make($product),
            'materialCombinationGroups' => $material_combination_groups,
        ]);
    }

    public function store(OrderRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            
            $product = Product::findOrFail($validated['productId']);
            $amounts = Product::getAllComponentAmounts(
                $request,
                $product,
            );
            $totalAmount = 0;
            foreach ($amounts as $key => $amount) {
                $totalAmount += $amount;
            }

            $order = new Order();
            $order->product_id = $validated['productId'];
            $order->status = OrderStatus::Pending->value;
            $order->customer_id = auth()->id();
            $order->order_type = OrderType::Product->value;
            $order->save();

            foreach ($amounts as $key => $amount) {

                $orderComponent = new OrderComponent();
                $orderComponent->order_unique_id = $order->order_unique_id;
                $orderComponent->order_id = $order->id;
                $orderComponent->product_id = $product->id;
                $orderComponent->key = $key;
                $orderComponent->parameter_json = $this->getParameterJson($request, $key); // MutatorでJSONに変換
                $orderComponent->amount = $amount;
                $orderComponent->save();

            }

            DB::commit();

            return [
                'result' => true,
                'order_unique_id' => $order->order_unique_id,
            ];

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'エラーが発生しました: ' . $e->getMessage() . ' ' . $e->getLine(),
            ], 500);

        }
    }

    public function groupType(Request $request)
    {
        $groupType = Product::getComponentGroupType($request->key);

        return [
            'group_type' => $groupType,
        ];
    }

    public function amounts(Request $request)
    {
        $product = Product::findOrFail($request->productId);
        $amounts = Product::getAllComponentAmounts(
            $request,
            $product,
        );

        return [
            'amounts' => $amounts,
        ];
    }

    public function complete(string $orderUniqueId)
    {
        $exists = Order::where('order_unique_id', $orderUniqueId)->exists();

        return Inertia::render('Store/Product/Complete', [
            'orderUniqueId' => $orderUniqueId,
        ]);
   }

    private function getParameterJson(Request $request, string $key): array
    {
        $parameters = $request->components[$key] ?? [];
        $materialId = $parameters['material']['id'] ?? null;
        
        if(! is_null($materialId)) {
            $material = Material::find($materialId);
            $parameters['material']['data'] = $material->toArray();
        }

        $colorId = $parameters['color']['id'] ?? null;
        if(! is_null($colorId)) {
            $color = MaterialColor::find($colorId);
            $parameters['color']['data'] = $color->toArray();
        }

        return $parameters;
    }
}

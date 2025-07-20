<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductCategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/ProductCategory/Index');
    }

    public function list(Request $request)
    {
        $product_categories = ProductCategory::query()
            ->whereSearch($request)
            ->orderby('sort_number', 'asc')
            ->paginate(15);

        return ProductCategoryResource::collection($product_categories);
    }

    public function input(?ProductCategory $product_category)
    {
        $product_categories = ProductCategory::query()
            ->orderby('sort_number', 'asc')
            ->get();
        ProductCategoryResource::withoutWrapping();

        return Inertia::render('Admin/ProductCategory/Input', [
            'productCategory' => ProductCategoryResource::make($product_category),
            'productCategories' => ProductCategoryResource::collection($product_categories),
        ]);
    }

    public function store(ProductCategoryRequest $request)
    {
        $product_category = new ProductCategory();

        return $this->save($request, $product_category);
    }

    public function update(ProductCategoryRequest $request, ProductCategory $product_category)
    {
        return $this->save($request, $product_category);
    }

    public function save(Request $request, ProductCategory $product_category)
    {
        $product_category->name = $request->name;
        $product_category->sort_number = $request->sort_number;
        $product_category->is_active = ($request->is_active === true);
        $result = $product_category->save();

        return ['result' => $result];
    }

    public function destroy(ProductCategory $product_category)
    {
        if($product_category->products()->exists()) {
            return response()->json([
                'message' => '商品が登録されているため削除できません。',
            ], 400);
        }

        $result = $product_category->delete();

        return ['result' => $result];
    }
}

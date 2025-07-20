<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemCategoryRequest;
use App\Http\Resources\ItemCategoryResource;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ItemCategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/ItemCategory/Index');
    }

    public function list(Request $request)
    {
        $item_categories = ItemCategory::query()
            ->whereSearch($request)
            ->orderby('sort_number', 'asc')
            ->paginate(15);

        return ItemCategoryResource::collection($item_categories);
    }

    public function input(?ItemCategory $item_category)
    {
        $item_categories = ItemCategory::query()
            ->orderby('sort_number', 'asc')
            ->get();
        ItemCategoryResource::withoutWrapping();

        return Inertia::render('Admin/ItemCategory/Input', [
            'itemCategory' => ItemCategoryResource::make($item_category),
            'itemCategories' => ItemCategoryResource::collection($item_categories),
        ]);
    }

    public function store(ItemCategoryRequest $request)
    {
        $item_category = new ItemCategory();

        return $this->save($request, $item_category);
    }

    public function update(ItemCategoryRequest $request, ItemCategory $item_category)
    {
        return $this->save($request, $item_category);
    }

    public function save(Request $request, ItemCategory $item_category)
    {
        $item_category->name = $request->name;
        $item_category->sort_number = $request->sort_number;
        $item_category->is_active = ($request->is_active === true);
        $result = $item_category->save();

        return ['result' => $result];
    }

    public function destroy(ItemCategory $item_category)
    {
        if($item_category->items()->exists()) {
            return response()->json([
                'message' => '商品が登録されているため削除できません。',
            ], 400);
        }

        $result = $item_category->delete();

        return ['result' => $result];
    }
}

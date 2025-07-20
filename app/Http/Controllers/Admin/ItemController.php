<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Http\Resources\ItemResource;
use App\Http\Resources\ShopResource;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemImage;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class ItemController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Item/Index');
    }

    public function list(Request $request)
    {
        $items = Item::query()
            ->with('category', 'shop', 'images')
            ->whereSearch($request)
            ->orderBy('id', 'desc')
            ->paginate(15);

        return ItemResource::collection($items);
    }

    public function input(?Item $item)
    {
        $item->load('shop', 'category', 'images');
        $shops = Shop::get();
        $item_categories = ItemCategory::get();

        ItemResource::withoutWrapping();
        ShopResource::withoutWrapping();

        return Inertia::render('Admin/Item/Input', [
            'item' => ItemResource::make($item),
            'shops' => ShopResource::collection($shops),
            'itemCategories' => $item_categories,
        ]);
    }

    public function store(ItemRequest $request)
    {
        $item = new Item();

        return $this->save($request, $item);
    }

    public function update(ItemRequest $request, Item $item)
    {
        return $this->save($request, $item);
    }

    private function save(Request $request, Item $item)
    {
        $result = false;

        DB::beginTransaction();

        try {

            $item->name = $request->name;
            $item->item_code = $request->item_code;
            $item->price = $request->price;
            $item->description = $request->description;
            $item->shop_id = $request->shop_id;
            $item->sort_number = $request->sort_number;
            $item->category_id = $request->category_id;
            $item->save();

            $this->sortImages($request, $item);
            $this->saveImages($request, $item);
            $this->deleteImages($request, $item);

            DB::commit();
            $result = true;

        } catch (\Exception $e) {

            DB::rollBack();
            logger()->error($e);

        }

        return [
            'result' => $result,
            'item_id' => $item->id,
        ];
    }

    private function sortImages(Request $request, Item $item)
    {
        $sort_image_ids = $request->input('sort_image_ids', []);
        $images = $item->images;

        foreach ($images as $image) {

            $sort_number = array_search($image->id, $sort_image_ids);

            if ($sort_number !== false) {

                $image->sort_number = $sort_number;
                $image->save();

            }

        }
    }

    private function saveImages(Request $request, Item $item)
    {
        $images = $request->file('new_images', []);

        $item->load('images');
        $max_sort_number = $item->images()->max('sort_number');

        foreach ($images as $image) {

            $path = $image->store('public/items/'. $item->id);
            $filename = basename($path);

            $item_image = new ItemImage();
            $item_image->item_id = $item->id;
            $item_image->filename = $filename;
            $item_image->sort_number = $max_sort_number + 1;
            $item_image->save();

        }
    }

    private function deleteImages(Request $request, Item $item)
    {
        $deleted_image_ids = $request->input('deleted_image_ids', []);
        $item_images = ItemImage::query()
            ->whereIn('id', $deleted_image_ids)
            ->where('item_id', $item->id)
            ->get();

        foreach ($item_images as $item_image) {

            $item_image->delete(); // イベント起動が必要なので、Eloquentのdeleteメソッドを使用

        }
    }

    public function destroy(Item $item)
    {
        $result = false;

        DB::beginTransaction();

        try {

            foreach ($item->images as $item_image) {

                $item_image->delete();

            }
            $item->delete();
            DB::commit();
            $result = true;

        } catch (\Exception $e) {

            DB::rollBack();
            logger()->error($e);

        }

        return [
            'result' => $result,
        ];
    }

    public function duplicate(Item $item)
    {
        $result = false;
        $item->load('images');

        DB::beginTransaction();

        try {

            $new_item = $item->replicate();
            $new_item->name = $item->name . ' (コピー)';
            $new_item->item_code = $item->item_code . date('YmdHis');
            $new_item->save();

            foreach ($item->images as $item_image) {

                $new_item_image = $item_image->replicate();
                $new_item_image->item_id = $new_item->id;
                $new_item_image->save();

                $src = $item_image->path;
                $dst = $new_item_image->path;
                $dir = dirname($dst);

                if (!File::exists($dir)) {

                    File::makeDirectory($dir, 0755, true, true);

                }

                File::copy($src, $dst);

            }

            DB::commit();
            $result = true;

        } catch (\Exception $e) {

            DB::rollBack();
            logger()->error($e);

        }

        return [
            'result' => $result,
        ];
    }
}

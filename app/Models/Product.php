<?php

namespace App\Models;

use App\Enums\ComponentGroup;
use App\Enums\ComponentGroupType;
use App\Enums\MainMaterialCombinationGroup;
use App\Enums\MaterialCombinationType;
use App\Events\ProductCreated;
use App\Events\ProductDeleting;
use App\Http\Resources\ProductResource;
use App\Mail\CalcAmountFailed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        'sketchfab_url',
        'detail_url',
    ];
    protected $dispatchesEvents = [
        'created' => ProductCreated::class,
        'deleting' => ProductDeleting::class,
    ];

    // Relationship
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    public function components()
    {
        return $this->hasMany(ProductComponent::class, 'product_id', 'id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class, 'id', 'product_id');
    }

    // Accessor
    public function getSketchfabUrlAttribute()
    {
        $key = $this->sketchfab_model_key;

        if(Str::length($key) > 0) {

            return 'https://sketchfab.com/models/' . $key;

        }

        return '';
    }

    public function getMaterialCombinationLabelAttribute()
    {
        if(MaterialCombinationType::has($this->material_combination_type)) {

            return MaterialCombinationType::from($this->material_combination_type)->getLabel();

        }

        return '';
    }

    public function getDetailUrlAttribute()
    {
        $id = (int) $this->id;

        if($id > 0) {

            return route('store.product.show', $this->id);

        }

        return '';
    }

    // Locals scope
    public function scopeWhereSearch($query, Request $request)
    {
        $query->when($request->q, function($query, $keyword) {

            $query->where('name', 'LIKE', '%'. $keyword .'%')
                ->orWhere('product_code', 'LIKE', '%'. $keyword .'%')
                ->orWhere('sketchfab_model_key', 'LIKE', '%'. $keyword .'%');

        });
    }

    // コンポーネントグループのタイプ取得
    public static function getComponentGroupType(string $key): string
    {
        return ComponentGroupType::fromComponentGroupKey($key)->value;
    }

    public static function getAllComponentAmounts(Request $request, Product $product)
    {
        $amounts = [];
        $product->load('components.materials.colors.color');
        $productResource = ProductResource::make($product);
        $materialCombinationGroupItems = $productResource->getMaterialCombinationGroups(); // 送信データは改ざんの危険があるため、バックエンドで定義されたデータをつかう

        foreach ($materialCombinationGroupItems as $materialCombinationGroupItem) {

            $key = $materialCombinationGroupItem['key'];
            $groupType = Product::getComponentGroupType($key);
            $amount = 0;

            // 分岐内容は「App\Enums\ComponentGroupType」を参照
            if($groupType === ComponentGroupType::Main->value) { // メイン

                try {

                    $amount += self::getMainTypeAmount($key, $request, $product);

                } catch (\Exception $e) {

                    self::sendMailAboutAmountFailure($e);
                    abort(500, $e->getMessage());

                }

            } else if($groupType === ComponentGroupType::Sub->value) { // サブ

                try {

                    $amount += self::getSubTypeAmount($key, $request, $product);

                } catch (\Exception $e) {

                    self::sendMailAboutAmountFailure($e);
                    abort(500, $e->getMessage());

                }

            } else if($groupType === ComponentGroupType::Common->value) { // 共通

                try {

                    $amount += self::getCommonTypeAmount($key, $request, $product);

                } catch (\Exception $e) {

                    self::sendMailAboutAmountFailure($e);
                    abort(500, 'Invalid component data');

                }

            } else if($groupType === 'logo') { // ロゴ

                try {

                    $amount += self::getLogoTypeAmount($key, $request, $product);

                } catch (\Exception $e) {

                    self::sendMailAboutAmountFailure($e);
                    abort(500, 'Invalid component data');

                }

            } else {

                $e = new \Exception('Invalid group type: ' . $groupType); // $groupTypeが不正な場合
                self::sendMailAboutAmountFailure($e);

            }

            $amounts[$key] = $amount;

        }

        return $amounts;
    }

    public static function getMainTypeAmount(
        string $key,
        Request $request,
        Product $product,
    ){
        $materialCombinationGroup = MainMaterialCombinationGroup::tryFrom($key);

        // 改ざんチェック
        if(is_null($materialCombinationGroup)) {

            throw new \Exception('Invalid component: ' . $key); // あるはずのキーがなければ、改ざんとみなす

        }

        // 素材（色は金額には関係ない）
        $combinationGroupKey = $materialCombinationGroup->value;
        $requestComponent = $request->components[$combinationGroupKey] ?? null;

        if(is_null($requestComponent)) {

            throw new \Exception('Invalid component key: ' . $combinationGroupKey); // あるはずのキーがなければ、改ざんとみなす

        }

        $materialId = (int) data_get($requestComponent, 'material.id', 0);

        if($materialId <= 0) {

            return 0; // 素材は未選択

        }

        // 金額の計算
        $mainComponentGroups = $materialCombinationGroup->getMainComponentGroups();
        $groupKeys = Arr::map($mainComponentGroups, function (ComponentGroup $component) {

            return $component->value;

        });
        $filteredComponents = $product->components
            ->filter(function ($component) use ($groupKeys) {

                return in_array($component->key, $groupKeys, true);

            });
        $amount = 0;

        foreach ($filteredComponents as $filteredComponent) { // 構成されるコンポーネントすべての金額を合計する

            $targetMaterial = $filteredComponent->materials
                ->where('material_id', $materialId)
                ->first();

            if(is_null($targetMaterial)) {

                throw new \Exception('Invalid materialId'); // 改ざんとみなす（コンポーネントの素材は必ず一致するはずだから）

            }

            $amount += (int) $targetMaterial->amount;

        }

        return $amount;
    }

    public static function getSubTypeAmount(
        string $key,
        Request $request,
        Product $product,
    ){
        $targetComponent = $product->components
            ->where('key', $key)
            ->first();
        $requestComponent = $request->components[$key] ?? null;

        if(is_null($targetComponent) || is_null($requestComponent)) {

            throw new \Exception('Invalid component key: ' . $key); // あるはずのキーがなければ、改ざんとみなす

        }

        $materialId = (int) data_get($requestComponent, 'material.id', 0);

        if($materialId <= 0) {

            return 0; // 素材は未選択

        }

        // 金額の計算
        $amount = 0;
        $targetMaterial = $targetComponent->materials
            ->where('material_id', $materialId)
            ->first();

        if(is_null($targetMaterial)) {

            throw new \Exception('Invalid materialId'); // 改ざんとみなす（コンポーネントの素材は必ず一致するはずだから）

        }

        $amount += (int) $targetMaterial->amount;

        return $amount;
    }

    public static function getCommonTypeAmount(
        string $key,
        Request $request,
        Product $product,
    ){
        return self::getSubTypeAmount($key, $request, $product); // 現状はサブと同じ処理だが、今後のことを考慮して分けておく
    }

    public static function getLogoTypeAmount(
        string $key,
        Request $request,
        Product $product,
    ){
        $targetComponent = $product->components
            ->where('key', $key)
            ->first();
        $requestComponent = $request->components[$key] ?? null;

        if(is_null($targetComponent) || is_null($requestComponent)) {

            throw new \Exception('Invalid component key: ' . $key); // あるはずのキーがなければ、改ざんとみなす

        }

        $logoEnabled = (boolean) $requestComponent['logo'] ?? false;

        if($logoEnabled === true) {

            return $targetComponent->materials
                ->sum('amount');

        }

        return 0;
    }

    static private function sendMailAboutAmountFailure($e)
    {
        $request = request();
        $message = 'エラーが発生しました: '.
            $e->getFile() ."\n".
            $e->getLine() ."\n".
            $e->getMessage() ."\n\n".
            'リクエストデータ: '.
            json_encode($request->all()) ."\n\n";
        $to = config('mail.admin.address');

        \Mail::to($to)->send(new CalcAmountFailed($message));
    }
}

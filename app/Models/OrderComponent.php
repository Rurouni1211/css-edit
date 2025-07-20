<?php

namespace App\Models;

use App\Events\OrderComponentDeleted;
use App\Events\OrderComponentSaved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ComponentGroup;
use App\Enums\MainMaterialCombinationGroup;
use App\Enums\ComponentGroupType;

class OrderComponent extends Model
{
    use HasFactory, SoftDeletes;

    protected $dispatchesEvents = [
        'saved' => OrderComponentSaved::class,
        'deleted' => OrderComponentDeleted::class,
    ];

    // Relationship
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    // Accessors
    public function getParameterJsonAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getKeyNameAttribute()
    {
        $key = $this->key;
        $mainMaterialCombinationGroup = MainMaterialCombinationGroup::tryFrom($key);

        if(! is_null($mainMaterialCombinationGroup)) {

            return $mainMaterialCombinationGroup->getLabel();

        }

        $componentGroup = ComponentGroup::tryFrom($key);

        if(! is_null($componentGroup)) {

            return $componentGroup->getLabel();

        }

        return '';
    }

    public function getComponentGroupTypeAttribute()
    {
        $key = $this->key;
        $mainMaterialCombinationGroup = MainMaterialCombinationGroup::tryFrom($key);
        $componentGroupType = null;

        if(! is_null($mainMaterialCombinationGroup)) {

            $componentGroupType = ComponentGroupType::fromComponentGroupKey($key);

        }

        $componentGroup = ComponentGroup::tryFrom($key);

        if(! is_null($componentGroup)) {

            $componentGroupType = ComponentGroupType::fromComponentKey($key);

        }

        return (! is_null($componentGroupType)) ? $componentGroupType->value : '';
    }

    // Mutators
    public function setParameterJsonAttribute($value)
    {
        $this->attributes['parameter_json'] = json_encode($value);
    }
}

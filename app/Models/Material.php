<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        'button_image_url',
    ];

    // Relationship
    public function normal_maps()
    {
        return $this->hasMany(MaterialNormalMap::class, 'material_id', 'id');
    }

    public function colors()
    {
        return $this->hasMany(MaterialColor::class, 'material_id', 'id');
    }

    public function specular_map()
    {
        return $this->hasOne(MaterialSpecularMap::class, 'material_id', 'id');
    }

    public function product_component_materials()
    {
        return $this->hasMany(ProductComponentMaterial::class, 'material_id', 'id');
    }

    // Locals scope
    public function scopeWhereSearch($query, $request)
    {
        $query->when($request->q, function($query, $keyword) {

            $query->where('name', 'LIKE', '%'.$keyword.'%')
                  ->orWhere('key', 'LIKE', '%'.$keyword.'%');

        });
    }

    public function getButtonImageUrlAttribute()
    {
        $buttonKeys = self::getMaterialButtonKeys();
        foreach ($buttonKeys as $key) {
            
            if (Str::startsWith($this->key, $key)) {
                return asset('images/material-buttons/' . $key . '.png');
            }
        }

        return null;
    }
    
    /**
     * 素材ボタン画像ファイル名を文字列長の降順で取得
     * 
     * @return array
     */
    public static function getMaterialButtonKeys(): array
    {
        $materialButtonsPath = public_path('images/material-buttons');
        
        return collect(\Illuminate\Support\Facades\File::files($materialButtonsPath))
            ->filter(function($file) {
                return $file->getExtension() === 'png';
            })
            ->map(function($file) {
                return $file->getFilenameWithoutExtension();
            })
            ->sortByDesc(function($filename) { // 長いファイルからチェックしないと、短いファイル名にマッチしてしまうので実装
                return strlen($filename);
            })
            ->values()
            ->toArray();
    }
}

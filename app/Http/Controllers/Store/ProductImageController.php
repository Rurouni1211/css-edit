<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ProductImageController extends Controller
{
    /**
     * テクスチャの小さい画像を取得する
     * 画像がない場合は生成してキャッシュする
     *
     * @param int $material_id マテリアルID
     * @param string $filename 画像ファイル名
     * @return \Illuminate\Http\Response
     */
    public function textureSmallImage($material_id, $filename)
    {
        // 元画像のパス
        $originalPath = 'public/materials/textures/' . $material_id . '/' . $filename;
        $originalFullPath = Storage::path($originalPath);
        
        // 小さい画像のパス（キャッシュ用）
        $smallImagePath = 'public/materials/textures/' . $material_id . '/small_' . $filename;
        $smallImageFullPath = Storage::path($smallImagePath);
        
        // 小さい画像が存在すればそれを返す
        if (Storage::exists($smallImagePath)) {
            return response()->file($smallImageFullPath);
        }
        
        // 元画像が存在しない場合はエラー
        if (!Storage::exists($originalPath)) {
            abort(404);
        }
        
        // 画像を読み込む
        $image = Image::make($originalFullPath);
        
        // 元の画像サイズを取得
        $originalWidth = $image->width();
        $originalHeight = $image->height();
        
        // アスペクト比を維持しながら、短い方を50pxにリサイズ
        if ($originalWidth >= $originalHeight) {
            // 横長または正方形の画像の場合（高さが短い）
            $image->resize(null, 50, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            // 縦長の画像の場合（幅が短い）
            $image->resize(50, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        
        // キャッシュ用に保存
        $directory = dirname($smallImageFullPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $image->save($smallImageFullPath);
        
        // 画像を返す
        return response()->file($smallImageFullPath);
    }
}

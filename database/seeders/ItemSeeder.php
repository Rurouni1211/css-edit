<?php

namespace Database\Seeders;

use Database\Factories\ItemFactory;
use Database\Factories\ItemImageFactory;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = storage_path('app/public/items');

        if (file_exists($path)) {
            File::deleteDirectory($path);
        }

        for($i = 0 ; $i < 10 ; $i++) {

            $item = ItemFactory::new()->create([
                'name' => 'テスト完成品名' . $i,
                'item_code' => Str::random(),
                'description' => "テスト完成品の説明文\nテスト完成品の説明文\nテスト完成品の説明文" . $i,
                'sort_number' => rand(1, 1000),
            ]);

            // 画像を追加
            for($j = 1 ; $j <= 3 ; $j++) {

                $item_image = ItemImageFactory::new()->create([
                    'item_id' => $item->id,
                    'filename' => Str::random() . '.jpg',
                    'sort_number' => $j + 1,
                ]);

                $copying_dir = dirname($item_image->path);

                if(! File::exists($copying_dir)) {

                    File::makeDirectory($copying_dir, 0777, true);

                }

                $src = storage_path('test/item_images/150x150_'. $j .'.png');
                File::copy($src, $item_image->path);

            }

        }
    }
}

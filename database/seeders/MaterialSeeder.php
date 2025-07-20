<?php

namespace Database\Seeders;

use App\Models\MaterialColor;
use App\Models\MaterialNormalMap;
use App\Models\MaterialSpecularMap;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Material;
use Illuminate\Support\Str;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->removeAllImages();
        $normal_map_image_paths = $this->getNormalMapImageFilePaths();

        $csv_path = storage_path('app/csv/initial_materials.csv');
        $lines = new \SplFileObject($csv_path);

        $materials = collect();

        foreach ($lines as $index => $line) {

            if($index > 0) {

                $materials->push(
                    str_getcsv($line)
                );

            }

        }

        $grouped_materials = $materials
            ->filter(function ($item) { // 空行を除外

                return ! empty($item[0]);

            })
            ->groupBy(function ($item) { // 「素材名」でグループ化

                return $item[0];

            })
            ->map(function ($group_items) { // 各素材の情報をまとめる

                $colors = [];
                $normal_maps = [];

                foreach ($group_items->toArray() as $group_item) {

                    $colors[] = [
                        'name' => $group_item[7],
                        'color_code' => $group_item[8],
                        'texture_filename' => $group_item[9],
                    ];
                    $normal_maps[] = [
                        'name' => $group_item[5],
                        'filename' => $group_item[6],
                    ];

                }

                return [
                    'name' => $group_items[0][0],
                    'key' => $group_items[0][1],
                    'glossiness' => $group_items[0][2],
                    'specular' => $group_items[0][3],
                    'specular_map' => $group_items[0][4],
                    'colors' => $colors,
                    'normal_maps' => $normal_maps,
                ];

            });

        foreach ($grouped_materials as $grouped_material) {

            $material = new Material();
            $material->name = $grouped_material['name'];
            $material->key = $grouped_material['key'];
            $material->glossiness = $grouped_material['glossiness'];
            $material->specular = $grouped_material['specular'];
            $material->save();

            if(Str::length($grouped_material['specular_map']) > 0) {

                $material_specular_map = new MaterialSpecularMap();
                $material_specular_map->material_id = $material->id;
                $material_specular_map->filename = Str::random(16) . '.png';
                $material_specular_map->original_filename = $grouped_material['specular_map'];
                $material_specular_map->save();

                $copying_dir = storage_path('app/public/materials/specular_maps/'. $material->id);

                if(! File::exists($copying_dir)) {

                    File::makeDirectory($copying_dir, 0777, true);

                }

                $src = storage_path('test/specular_maps/specular_map_sample.png');
                $dst = $copying_dir .'/'. $material_specular_map->filename;
                File::copy($src, $dst);

            }

            // Colors or Texture
            foreach ($grouped_material['colors'] as $color_index => $color) {

                $original_texture_filename = null;
                $texture_filename = null;

                if(app()->environment('local') && $color_index % 3 === 0) {

                    $original_texture_filename = $color['texture_filename'];
                    $texture_filename = Str::random(16) . '.png';

                }

                $material_color = new MaterialColor();
                $material_color->material_id = $material->id;
                $material_color->name = $color['name'];
                $material_color->color_code = $color['color_code'];
                $material_color->original_texture_filename = $original_texture_filename;
                $material_color->texture_filename = $texture_filename;
                $material_color->save();

                if(! is_null($texture_filename)) { // copy image file

                    $copying_dir = storage_path('app/public/materials/textures/'. $material->id);

                    if(! File::exists($copying_dir)) {

                        File::makeDirectory($copying_dir, 0777, true);

                    }

                    $src = storage_path('test/textures/texture_sample.png');
                    $dst = $copying_dir .'/'. $material_color->texture_filename;
                    File::copy($src, $dst);

                }

            }

            // Normal maps
            foreach ($grouped_material['normal_maps'] as $normal_map) {

                $normal_map_image_path = Arr::random($normal_map_image_paths);
                $original_filename = basename($normal_map_image_path);
                $filename = (Str::length($original_filename) > 0)
                    ? Str::random(16) . '.png'
                    : null;

                $material_normal_map = new MaterialNormalMap();
                $material_normal_map->material_id = $material->id;
                $material_normal_map->name = $normal_map['name'];
                $material_normal_map->original_filename = $original_filename;
                $material_normal_map->filename = $filename;
                $material_normal_map->save();

                if(! is_null($filename)) { // copy image file

                    $copying_dir = storage_path('app/public/materials/normal_maps/'. $material->id);

                    if(! File::exists($copying_dir)) {

                        File::makeDirectory($copying_dir, 0777, true);

                    }

                    $src = Arr::random($normal_map_image_paths);
                    $dst = $copying_dir .'/'. $filename;
                    File::copy($src, $dst);

                }

            }

        }
    }

    private function removeAllImages()
    {
        $paths = [
            storage_path('app/public/materials/normal_maps'),
            storage_path('app/public/materials/textures'),
            storage_path('app/public/materials/specular_maps'),
        ];

        foreach ($paths as $path) {

            $files = glob($path . '/*');

            foreach ($files as $file) {

                if (basename($file) !== '.gitkeep') {

                    if (is_dir($file)) {

                        File::deleteDirectory($file);

                    } else {

                        File::delete($file);

                    }

                }

            }
        }
    }

    private function getNormalMapImageFilePaths()
    {
        $dir = storage_path('test/normal_maps');
        $files = glob($dir . '/*');
        $paths = [];

        foreach ($files as $file) {

            $paths[] = $file;

        }

        return $paths;
    }
}

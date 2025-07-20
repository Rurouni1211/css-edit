<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaterialRequest;
use App\Http\Resources\MaterialResource;
use App\Models\Material;
use App\Models\MaterialColor;
use App\Models\MaterialNormalMap;
use App\Models\MaterialSpecularMap;
use App\Traits\StorageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MaterialController extends Controller
{
    use StorageTrait;

    public function index()
    {
        return Inertia::render('Admin/Material/Index');
    }

    public function list(Request $request)
    {
        $materials = Material::query()
            ->with('colors', 'normal_maps', 'specular_map')
            ->whereSearch($request)
            ->paginate(15);

        return MaterialResource::collection($materials);
    }

    public function input(?Material $material)
    {
        $material->load('colors', 'normal_maps', 'specular_map');
        $materialButtonKeys = Material::getMaterialButtonKeys();
        MaterialResource::withoutWrapping();

        return Inertia::render('Admin/Material/Input', [
            'material' => MaterialResource::make($material),
            'materialButtonKeys' => $materialButtonKeys,
        ]);
    }

    public function store(MaterialRequest $request)
    {
        return $this->save($request, new Material());
    }

    public function update(MaterialRequest $request, Material $material)
    {
        return $this->save($request, $material);
    }

    private function save(Request $request, Material $material)
    {
        $result = false;

        DB::beginTransaction();

        $material->name = $request->name;
        $material->key = $request->key;
        $material->glossiness = $request->glossiness;
        $material->specular = $request->specular;

        try {

            $material->save();
            $this->deleteFiles($request);
            $this->saveFiles($request, $material);

            DB::commit();
            $result = true;

        } catch (\Exception $e) {

            DB::rollBack();
            logger()->error(
                $e->getMessage() . PHP_EOL . $e->getLine()
            );
            throw $e;

        }

        return [
            'result' => $result,
            'material' => $material->load('colors', 'normal_maps', 'specular_map'),
        ];
    }

    private function saveFiles(Request $request, Material $material)
    {
        // Colors
        $deleting_color_file_ids = $request->input('deleting_ids.color_files', []);
        $deleting_color_file_ids = Arr::map($deleting_color_file_ids, function ($id) {

            return (int) $id;

        });

        foreach ($request->colors as $index => $color) {

            $material_color = MaterialColor::firstOrNew([
                'id' => data_get($color, 'id'),
            ]);
            $material_color->material_id = $material->id;
            $material_color->name = $color['name'];
            $material_color->color_code = data_get($color, 'color_code', '');

            $file_key = 'colors.' . $index . '.texture_file';

            if($request->hasFile($file_key)) {

                $file = $request->file($file_key);
                $filename = Str::random(20) .'.'. $file->getClientOriginalExtension();
                $dir = 'public/materials/textures/'. $material->id;
                $this->saveFileInPublicStorage($dir, $file, $filename);
                $material_color->original_texture_filename = $file->getClientOriginalName();
                $material_color->texture_filename = $filename;

            } else if(in_array(intval($material_color->id), $deleting_color_file_ids, true)) {

                @unlink($material_color->texture_path);
                $material_color->texture_filename = '';
                $material_color->original_texture_filename = '';

            }

            $material_color->save();

        }

        // Normal maps
        $deleting_normal_map_file_ids = $request->input('deleting_ids.normal_map_files', []);
        $deleting_normal_map_file_ids = Arr::map($deleting_normal_map_file_ids, function ($id) {

            return (int) $id;

        });

        foreach ($request->normal_maps as $index => $normal_map) {

            $material_normal_map = MaterialNormalMap::firstOrNew([
                'id' => data_get($normal_map, 'id'),
            ]);
            $material_normal_map->material_id = $material->id;
            $material_normal_map->name = $normal_map['name'];

            $file_key = 'normal_maps.' . $index . '.file';

            if($request->hasFile($file_key)) {

                $file = $request->file($file_key);
                $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
                $dir = 'public/materials/normal_maps/'. $material->id;
                $this->saveFileInPublicStorage($dir, $file, $filename);

                $material_normal_map->original_filename = $file->getClientOriginalName();
                $material_normal_map->filename = $filename;

            } else if(in_array(intval($material_normal_map->id), $deleting_normal_map_file_ids, true)) {

                @unlink($material_color->path);
                $material_normal_map->filename = '';
                $material_normal_map->original_filename = '';

            }

            $material_normal_map->save();

        }

        // Specular map
        if($request->hasFile('specular_map.file')) {

            $material_specular_map = MaterialSpecularMap::firstOrNew([
                'id' => $request->input('specular_map.id'),
            ]);

            $file = $request->file('specular_map.file');
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $dir = 'public/materials/specular_maps/'. $material->id;
            $this->saveFileInPublicStorage($dir, $file, $filename);

            $material_specular_map->material_id = $material->id;
            $material_specular_map->original_filename = $file->getClientOriginalName();
            $material_specular_map->filename = $filename;
            $material_specular_map->save();

        }
    }

    private function deleteFiles(Request $request)
    {
        $deleting_data = $request->get('deleting_ids', []);

        foreach ($deleting_data as $key => $deleting_ids) {

            if($key === 'colors') {

                foreach ($deleting_ids as $deleting_id) {

                    $material_color = MaterialColor::query()
                        ->where('id', $deleting_id)
                        ->first();
                    $material_color->product_component_material_colors()->delete(); // 商品にセットされたカラーも削除
                    $material_color->delete();

                }

            } else if($key === 'normal_maps') {

                foreach ($deleting_ids as $deleting_id) {

                    $material_normal_map = MaterialNormalMap::query()
                        ->where('id', $deleting_id)
                        ->first();
                    $material_normal_map->product_component_material_normal_maps()->delete(); // 商品にセットされたノーマルマップも削除
                    $material_normal_map->delete();

                }

            } else if($key === 'specular_maps') {

                $deleting_id = $deleting_ids[0];
                MaterialSpecularMap::query()
                    ->where('id', $deleting_id)
                    ->first()
                    ->delete();

            }

        }
    }

    public function destroy(Request $request, Material $material)
    {
        $material->load('product_component_materials');

        if($material->product_component_materials->isNotEmpty()) {

            return response()->json([
                'result' => false,
                'message' => 'この素材は製品の構成パーツとして登録されているため削除できません。',
            ], 442);

        }

        $result = false;

        DB::beginTransaction();

        try {

            foreach ($material->colors as $color) {

                $color->delete(); // イベントを発火させるため個別削除

            }

            foreach ($material->normal_maps as $normal_map) {

                $normal_map->delete(); // イベントを発火させるため個別削除

            }

            if(! is_null($material->specular_map)) {

                $material->specular_map->delete(); // イベントを発火させるため個別削除

            }

            $material->delete();

            DB::commit();
            $result = true;

        } catch (\Exception $e) {

            DB::rollBack();
            logger()->error(
                $e->getMessage() . PHP_EOL . $e->getLine()
            );

        }

        return [
            'result' => $result,
        ];
    }
}

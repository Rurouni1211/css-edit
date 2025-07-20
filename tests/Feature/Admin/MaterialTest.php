<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Material;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Tests\TestCase;

class MaterialTest extends TestCase
{
    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::first();
    }

    public function test_can_search_material()
    {
        $this->actingAs($this->admin, 'admin');

        $material = Material::query()
            ->inRandomOrder()
            ->first();

        $url = route('admin.material.list') .'?q='. urlencode($material->name);
        $response = $this->get($url)->assertStatus(200);
        $count = count($response->json());
        $this->assertGreaterThan(0, $count);
    }

    public function test_can_add_material(): void
    {
        $this->actingAs($this->admin, 'admin');

        $url = route('admin.material.store');
        $specular_filename = Str::random() . '.jpg';
        $color_filename = Str::random() . '.jpg';
        $normal_map_filename = Str::random() . '.jpg';
        $data = [
            'name' => 'テスト素材名'. Str::random(),
            'key' => Str::random(),
            'glossiness' => 0.1 * rand(1, 10),
            'specular' => 0.1 * rand(1, 10),
            'specular_map' => [
                'file' => UploadedFile::fake()->image($specular_filename),
            ],
            'colors' => [
                [
                    'name' => 'テスト色名',
                    'color_code' => $this->getRandomHexColor(),
                    'texture_file' => UploadedFile::fake()->image($color_filename),
                ]
            ],
            'normal_maps' => [
                [
                    'name' => 'テストノーマルマップ名',
                    'file' => UploadedFile::fake()->image($normal_map_filename),
                ]
            ]
        ];
        $response = $this->post($url, $data)->assertStatus(200);
        $response_data = $response->json();

        $this->assertMaterialDatabases($data, $response_data);
    }

    public function test_can_update_material(): void
    {
        $this->actingAs($this->admin, 'admin');

        $material = Material::query()
            ->inRandomOrder()
            ->first();

        $url = route('admin.material.update', $material);
        $specular_filename = Str::random() . '.jpg';
        $color_filename = Str::random() . '.jpg';
        $normal_map_filename = Str::random() . '.jpg';
        $data = [
            'name' => 'テスト素材名'. Str::random(),
            'key' => Str::random(),
            'glossiness' => 0.1 * rand(1, 10),
            'specular' => 0.1 * rand(1, 10),
            'specular_map' => [
                'file' => UploadedFile::fake()->image($specular_filename),
            ],
            'colors' => [
                [
                    'name' => 'テスト色名',
                    'color_code' => $this->getRandomHexColor(),
                    'texture_file' => UploadedFile::fake()->image($color_filename),
                ]
            ],
            'normal_maps' => [
                [
                    'name' => 'テストノーマルマップ名',
                    'file' => UploadedFile::fake()->image($normal_map_filename),
                ]
            ]
        ];
        $response = $this->put($url, $data)->assertStatus(200);
        $response_data = $response->json();

        $this->assertMaterialDatabases($data, $response_data);
    }

    private function assertMaterialDatabases($data, $response_data)
    {
        $specular_filename = $data['specular_map']['file']->getClientOriginalName();
        $color_filename = $data['colors'][0]['texture_file']->getClientOriginalName();
        $normal_map_filename = $data['normal_maps'][0]['file']->getClientOriginalName();

        $material_data = Arr::only($data, ['name', 'key', 'glossiness', 'specular']);
        $this->assertDatabaseHas('materials', $material_data);
        $this->assertDatabaseHas('material_specular_maps', [
            'material_id' => $response_data['material']['id'],
            'original_filename' => $specular_filename,
        ]);
        $this->assertDatabaseHas('material_colors', [
            'material_id' => $response_data['material']['id'],
            'original_texture_filename' => $color_filename,
        ]);
        $this->assertDatabaseHas('material_normal_maps', [
            'material_id' => $response_data['material']['id'],
            'original_filename' => $normal_map_filename,
        ]);
    }

    public function test_can_delete_material_color_n_normal_map() // test_can_delete_material() より先に実行
    {
        $this->actingAs($this->admin, 'admin');

        $normal_map_filename = Str::random() . '.jpg';
        $material = Material::query()
            ->with('colors', 'normal_maps')
            ->inRandomOrder()
            ->has('colors')
            ->has('normal_maps')
            ->first();
        $url = route('admin.material.update', $material);
        $data = [
            'name' => 'テスト素材名'. Str::random(),
            'key' => Str::random(),
            'glossiness' => 0.1 * rand(1, 10),
            'specular' => 0.1 * rand(1, 10),
            'colors' => [
                [
                    'name' => 'テスト色名',
                    'color_code' => $this->getRandomHexColor(),
                ]
            ],
            'normal_maps' => [
                [
                    'name' => 'テストノーマルマップ名',
                    'file' => UploadedFile::fake()->image($normal_map_filename),
                ]
            ],
            'deleting_ids' => [
                'colors' => [$material->colors->random()->first()->id],
                'normal_maps' => [$material->normal_maps->random()->first()->id],
            ]
        ];
        $this->put($url, $data)->assertStatus(200);
    }

    public function test_can_delete_material()
    {
        $this->actingAs($this->admin, 'admin');

        $material = Material::query()
            ->inRandomOrder()
            ->doesntHave('product_component_materials')
            ->first();

        $url = route('admin.material.destroy', $material);
        $this->delete($url)->assertStatus(200);
        $this->assertSoftDeleted('materials', ['id' => $material->id]);
    }

    private function getRandomHexColor()
    {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
}

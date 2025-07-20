<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductComponent;
use App\Models\ProductComponentGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DataListController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $modes = $request->input('modes', []);

        foreach ($modes as $mode) {

            $method_name = Str::camel('get_'. $mode .'_data');

            if (method_exists($this, $method_name) && is_callable([$this, $method_name])) {

                $data[$mode] = $this->$method_name();

            }

        }

        return $data;
    }

    // パーツ（components）
    private function getComponentsData()
    {
        return ProductComponent::query()
            ->selectRaw('name, COUNT(name) AS count')
            ->groupBy('name')
            ->orderByRaw('COUNT(name) DESC')
            ->limit(15)
            ->get();
    }
}

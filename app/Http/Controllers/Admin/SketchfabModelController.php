<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SketchfabModelResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SketchfabModelController extends Controller
{
    private $api_token;

    public function __construct()
    {
        $this->api_token = config('services.sketchfab.api_token');
    }

    public function list(Request $request)
    {
        $cursor = $request->input('cursor', '');
        $url = 'https://api.sketchfab.com/v3/me/models?archives_flavours=false&cursor='. $cursor;
        $response = Http::withToken($this->api_token, 'Token')->get($url);

        return [
            'cursors' => $response->json('cursors'),
            'models' => SketchfabModelResource::collection($response->json('results')),
        ];
    }
}

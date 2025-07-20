<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopRequest;
use App\Http\Resources\ShopResource;
use App\Mail\ShopProfileSaved;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Shop/Index');
    }

    public function list(Request $request)
    {
        return Shop::query()
            ->whereSearch($request)
            ->paginate(15);
    }

    public function input(?Shop $shop)
    {
        $password_length = config('auth.password_length');
        ShopResource::withoutWrapping();

        return Inertia::render('Admin/Shop/Input', [
            'shop' => ShopResource::make($shop),
            'passwordLength' => $password_length,
        ]);
    }

    public function store(ShopRequest $request)
    {
        $shop = new Shop();

        return $this->save($request, $shop);
    }

    public function update(ShopRequest $request, Shop $shop)
    {
        return $this->save($request, $shop);
    }

    public function save(Request $request, Shop $shop)
    {
        $shop->name = $request->name;
        $shop->email = $request->email;

        if($request->filled('password')) {

            $shop->password = bcrypt($request->password);

        }

        $result = $shop->save();

        if($result === true && $request->has_password && $request->has_notification) {

            Mail::to($shop->email)
                ->send(new ShopProfileSaved(
                    $shop,
                    $shop->email,
                    $request->password
                ));

        }

        return ['result' => $result];
    }

    public function destroy(Shop $shop)
    {
        $result = $shop->delete();

        return ['result' => $result];
    }
}

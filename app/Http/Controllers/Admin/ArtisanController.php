<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArtisanRequest;
use App\Http\Resources\ArtisanResource;
use App\Mail\ArtisanProfileSaved;
use App\Models\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ArtisanController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Artisan/Index');
    }

    public function list(Request $request)
    {
        return Artisan::query()
            ->whereSearch($request)
            ->paginate(15);
    }

    public function input(?Artisan $artisan)
    {
        $password_length = config('auth.password_length');

        return Inertia::render('Admin/Artisan/Input', [
            'artisan' => $artisan,
            'passwordLength' => $password_length,
        ]);
    }

    public function store(ArtisanRequest $request)
    {
        $artisan = new Artisan();

        return $this->save($request, $artisan);
    }

    public function update(ArtisanRequest $request, Artisan $artisan)
    {
        return $this->save($request, $artisan);
    }

    public function save(Request $request, Artisan $artisan)
    {
        $artisan->name = $request->name;
        $artisan->email = $request->email;

        if($request->filled('password')) {

            $artisan->password = bcrypt($request->password);

        }

        $result = $artisan->save();

        if($result === true && $request->has_password && $request->has_notification) {

            Mail::to($artisan->email)
                ->send(new ArtisanProfileSaved(
                    $artisan,
                    $artisan->email,
                    $request->password
                ));

        }

        return ['result' => $result];
    }

    public function destroy(Artisan $artisan)
    {
        $result = $artisan->delete();

        return ['result' => $result];
    }
}

<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait StorageTrait
{
    public function saveFileInPublicStorage($dir, $file, $filename)
    {
        return Storage::putFileAs(
            $dir,
            $file,
            $filename,
            [
                'visibility' => 'public',
                'directory_visibility' => 'public'
            ],
        );
    }
}

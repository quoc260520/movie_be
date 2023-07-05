<?php

namespace App\Repositories\FileSystem;

use Illuminate\Support\Facades\Storage;

class FileSystemRepository
{
    public function uploadFile($files) {
        // $googleDisk = Storage::disk('google');
        // dd($googleDisk->put('exam.txt','adada'));
        return 'https://www.cgv.vn/media/catalog/product/cache/1/image/c5f0a1eff4c394a251036189ccddaacd/p/o/poster_official_preiview.jpg';
    }
}

<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Storage;

trait FileStorage
{
    public function uploadFile($idUsecase, $data, $params){
        try {
            $file = $data['file'];
            $path = Storage::putFileAs($params['type'], $file, "{$params['type']}_{$params['name_usecase']}_{$idUsecase}.{$file->extension()}");

            Storage::disk('minio')->setVisibility($path, 'public');
            $url = Storage::url($path);

            return $url;
        } catch (Exception $err) {
            throw new Exception('File gagal di upload, coba beberapa saat lagi');
        }
    }
}

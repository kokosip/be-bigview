<?php

namespace App\Traits;

use Exception;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Log;

trait FileStorage
{
    public function uploadFile($idUsecase, $data, $params) {
        try {
            $file = $data['file'];
    
            $s3Client = new S3Client([
                'version' => env('MINIO_VERSION', 'latest'),
                'region'  => env('MINIO_REGION', 'us-east-1'),
                'endpoint' => env('MINIO_ENDPOINT'),
                'use_path_style_endpoint' => true,
                'credentials' => [
                    'key'    => env('MINIO_KEY'),
                    'secret' => env('MINIO_SECRET'),
                ],
            ]);

            $bucket = env('MINIO_BUCKET');
            $key = "{$params['dir']}/{$params['type']}_{$params['name_usecase']}_{$idUsecase}.{$file->extension()}";

            $contentType = mime_content_type($file->getPathname());
    
            $result = $s3Client->putObject([
                'Bucket' => $bucket,
                'Key'    => $key,
                'SourceFile' => $file->getPathname(),
                'ContentType' => $contentType,
                'ContentDisposition' => 'inline',
            ]);
            return $key;
        } catch (AwsException $e) {
            Log::error('AWS Error', ['error' => $e->getMessage()]);
            throw new Exception('Pengunggahan file tidak berhasil akibat masalah storage server. Mohon coba lagi nanti');
        } catch (Exception $err) {
            Log::error('Error', ['error' => $err->getMessage()]);
            throw new Exception('Pengunggahan file tidak berhasil. Mohon coba lagi nanti');
        }
    }

    public function getFile($path){
        if(empty($path)) $path = 'image_not_found.png';

        $url = env('MINIO_ENDPOINT') . '/' . env('MINIO_BUCKET') . '/' . $path;

        return $url;
    }

    public function deleteFile($path){
        try {
            $s3Client = new S3Client([
                'version' => env('MINIO_VERSION', 'latest'),
                'region'  => env('MINIO_REGION', 'us-east-1'),
                'endpoint' => env('MINIO_ENDPOINT'),
                'use_path_style_endpoint' => true,
                'credentials' => [
                    'key'    => env('MINIO_KEY'),
                    'secret' => env('MINIO_SECRET'),
                ],
            ]);

            $bucket = env('MINIO_BUCKET');

            $result = $s3Client->deleteObject([
                'Bucket' => $bucket,
                'Key'    => $path,
            ]);
    
            return true;
        } catch (AwsException $e) {
            Log::error('AWS Error', ['error' => $e->getMessage()]);
            throw new Exception('Pengapusan file tidak berhasil akibat masalah storage server. Mohon dicoba lagi setelah beberapa saat');
        } catch (Exception $err) {
            Log::error('Error', ['error' => $err->getMessage()]);
            throw new Exception('Pengapusan file tidak berhasil akibat masalah storage server. Mohon dicoba lagi setelah beberapa saat');
        }
    } 
}

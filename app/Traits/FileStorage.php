<?php

namespace App\Traits;

use App\Exceptions\ErrorResponse;
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
            throw new ErrorResponse(type: 'AWS Server Error', message: 'Pengunggahan file tidak berhasil. Mohon coba lagi nanti');
        } catch (Exception $err) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Pengunggahan file tidak berhasil. Mohon coba lagi nanti');
        }
    }

    public function getFile($path){
        if(empty($path)) $path = 'image_not_found.png';

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

        try {
            $s3Client->headObject([
                'Bucket' => $bucket,
                'Key'    => $path,
            ]);
    
            $url = $s3Client->getObjectUrl($bucket, $path);
        } catch (AwsException $e) {
            if ($e->getStatusCode() == 404) {
                $url = $s3Client->getObjectUrl($bucket, 'image_not_found.png');
            } else {
                throw new ErrorResponse(type: 'AWS Server Error', message: 'Pengambilan file tidak berhasil. Mohon coba lagi nanti');
            }
        } catch (Exception $err) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Pengapusan file tidak berhasil akibat masalah storage server. Mohon dicoba lagi setelah beberapa saat');
        }
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
            throw new ErrorResponse(type: 'AWS Error', message: 'Pengapusan file tidak berhasil akibat masalah storage server. Mohon dicoba lagi setelah beberapa saat', statusCode: 403);
        } catch (Exception $err) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Pengapusan file tidak berhasil akibat masalah storage server. Mohon dicoba lagi setelah beberapa saat', statusCode:500);
        }
    } 
}

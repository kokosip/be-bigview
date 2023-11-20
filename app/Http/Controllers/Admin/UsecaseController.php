<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UsecaseServices;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class UsecaseController extends Controller
{
    use ApiResponse;
    protected $usecaseService;

    public function __construct(UsecaseServices $usecaseService)
    {
        $this->usecaseService = $usecaseService;
    }

    public function listUsecase(Request $request){
        $search = $request->input("search");
        $perPage = is_null($request->input('per_page')) ? 10 : $request->input('per_page');

        try{
            [$data, $metadata] = $this->usecaseService->getListUsecase($search, $perPage);

            return $this->successResponse(data: $data, metadata: $metadata);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
}

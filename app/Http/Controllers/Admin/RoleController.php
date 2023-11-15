<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\RoleServices;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use ApiResponse;
    protected $roleService;

    public function __construct(RoleServices $roleService)
    {
        $this->roleService = $roleService;
    }

    public function addRole(Request $request){
        $validator = Validator::make($request->all(), [
            'name_menu' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->roleService->insertRole($validator->validate());

            return $this->successResponse($data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
}

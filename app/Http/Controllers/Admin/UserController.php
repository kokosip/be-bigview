<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UserServices;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponse;
    protected $userService;

    public function __construct(UserServices $userService)
    {
        $this->userService = $userService;
    }

    public function listUser(Request $request){
        $search = $request->input("search");
        $perPage = is_null($request->input('per_page')) ? 10 : $request->input('per_page');

        try{
            [$data, $metadata] = $this->userService->getListUser($search, $perPage);

            return $this->successResponse(data: $data, metadata: $metadata);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function addUser(Request $request){
        $validator = Validator::make($request->all(), [
            'id_usecase' => 'required',
            'name' => 'required',
            'username' => 'required',
            'email' => 'required',
            'id_role' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->userService->insertUser($validator->validate());

            return $this->successResponse(data: $data, message: "User Berhasil ditambahkan");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function getUserById($id_user){
        try{
            $data = $this->userService->getUserById($id_user);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function deleteUser($id_user){
        try{
            $this->userService->deleteUser($id_user);

            return $this->successResponse(message: "Data Berhasil dihapus");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\MenuServices;
use App\Services\Admin\RoleServices;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    use ApiResponse;
    protected $roleService;
    protected $menuService;

    public function __construct(RoleServices $roleService, MenuServices $menuService)
    {
        $this->roleService = $roleService;
        $this->menuService = $menuService;
    }

    public function addRole(Request $request){
        $validator = Validator::make($request->all(), [
            'nama_role' => 'required|string',
            'level' => 'required',
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

    public function listRole(Request $request){
        $search = $request->input("search");
        $perPage = is_null($request->input('per_page')) ? 5 : $request->input('per_page');

        try{
            [$data, $metadata] = $this->roleService->getListRole($search, $perPage);

            return $this->successResponse(data: $data, metadata: $metadata);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function getRoleById($id_role){
        try{
            $data = $this->roleService->getRoleById($id_role);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function deleteRole($id_role){
        try{
            $this->roleService->deleteRole($id_role);

            return $this->successResponse(message: "Data Berhasil dihapus");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function updateRole(Request $request, $id_role){
        $validator = Validator::make($request->all(), [
            'nama_role' => 'required|string',
            'level' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $this->roleService->updateRole($validator->validate(), $id_role);

            return $this->successResponse(message: "Data Berhasil di Update");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function listRoleMenu(Request $request){
        $validator = Validator::make($request->all(), [
            'id_role' => 'required',
            'id_parent' => 'nullable'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->menuService->listRoleMenu($validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function listSubMenu(Request $request){
        $validator = Validator::make($request->all(), [
            'id_parent' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->menuService->listSubMenu($validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function addRoleMenu(Request $request){
        $validator = Validator::make($request->all(), [
            'id_role' => 'required',
            'id_menu' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $this->menuService->addRoleMenu($validator->validate());

            return $this->successResponse(message: "Role Menu Berhasil ditambahkan");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function deleteRoleMenu(Request $request){
        $validator = Validator::make($request->all(), [
            'id_role' => 'required',
            'id_menu' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $this->menuService->deleteRoleMenu($validator->validate());

            return $this->successResponse(message: "Role Menu Berhasil dihapus");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
}

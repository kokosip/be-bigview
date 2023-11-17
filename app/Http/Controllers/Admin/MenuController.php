<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\MenuServices;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    use ApiResponse;
    protected $menuService;

    public function __construct(MenuServices $menuService)
    {
        $this->menuService = $menuService;
    }

    public function addMenu(Request $request) {
        $validator = Validator::make($request->all(), [
            'name_menu' => 'required|string',
            'icon' => 'nullable',
            'link' => 'required',
            'id_parent' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->menuService->insertMenu($validator->validate());

            return $this->successResponse($data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function filterMenuUtama(){
        try{
            $data = $this->menuService->getMenuUtama();

            return $this->successResponse($data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function listMenu(Request $request){
        $search = $request->input("search");
        $perPage = is_null($request->input('per_page')) ? 5 : $request->input('per_page');

        try{
            [$data, $metadata] = $this->menuService->getListMenu($search, $perPage);

            return $this->successResponse(data: $data, metadata: $metadata);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function getMenuById($id_menu){
        try{
            $data = $this->menuService->getMenuById($id_menu);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function deleteMenu($id_menu){
        try{
            $this->menuService->deleteMenu($id_menu);

            return $this->successResponse(message: "Data Berhasil dihapus");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function updateMenu(Request $request, $id_menu){
        $validator = Validator::make($request->all(), [
            'name_menu' => 'required|string',
            'icon' => 'required',
            'link' => 'required',
            'id_parent' => 'required',
            'sort' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $this->menuService->updateMenu($validator->validate(), $id_menu);

            return $this->successResponse(message: "Data Berhasil di Update");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
}

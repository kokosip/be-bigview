<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\MenuRepositories;
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
}

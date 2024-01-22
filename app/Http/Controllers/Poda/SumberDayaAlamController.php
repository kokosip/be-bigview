<?php

namespace App\Http\Controllers\Poda;

use App\Http\Controllers\Controller;
use App\Services\Poda\SumberDayaAlamServices;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SumberDayaAlamController extends Controller
{
    use ApiResponse;
    protected $sdaService;
    protected $idUsecase;

    public function __construct(SumberDayaAlamServices $sdaService)
    {
        $this->sdaService = $sdaService;
        $this->idUsecase = Auth::user()->id_usecase;
    }

    public function listIndikator($subject){
        try{
            $data = $this->sdaService->getListIndikator($this->idUsecase, $subject);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function listJenis(Request $request, $subject){
        $validator = Validator::make($request->all(), [
            'indikator' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sdaService->getListJenis($this->idUsecase, $subject, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function listTahun(Request $request, $subject){
        $validator = Validator::make($request->all(), [
            'indikator' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sdaService->getListTahun($this->idUsecase, $subject, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function periodeSda(Request $request, $subject){
        $validator = Validator::make($request->all(), [
            'indikator' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sdaService->getPeriodeSda($this->idUsecase, $subject, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function mapSda(Request $request, $subject){
        $validator = Validator::make($request->all(), [
            'indikator' => 'required',
            'jenis' => 'required',
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sdaService->getMapSda($this->idUsecase, $subject, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function barSda(Request $request, $subject){
        $validator = Validator::make($request->all(), [
            'indikator' => 'required',
            'jenis' => 'required',
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sdaService->getBarSda($this->idUsecase, $subject, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function areaSda(Request $request, $subject){
        $validator = Validator::make($request->all(), [
            'indikator' => 'required',
            'jenis' => 'required',
            'periode' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sdaService->getAreaSda($this->idUsecase, $subject, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
}

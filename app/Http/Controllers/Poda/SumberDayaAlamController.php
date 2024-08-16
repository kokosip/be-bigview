<?php

namespace App\Http\Controllers\Poda;

use App\Http\Controllers\Controller;
use App\Services\Poda\SumberDayaAlamServices;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SumberDayaAlamController extends Controller
{
    use ApiResponse;
    protected $sdaService;
    protected $level;
    protected $idUsecase;

    public function __construct(SumberDayaAlamServices $sdaService)
    {
        $this->sdaService = $sdaService;
        $this->level = Auth::user()->level ?? null;
        $this->idUsecase = Auth::user()->id_usecase ?? null;
    }

    public function listIndikator(Request $request, $subject){
        if ($this->level >= 1) {
            $data = $this->sdaService->getListIndikator($this->idUsecase, $subject);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->sdaService->getListIndikator($validator->validate()['id_usecase'], $subject);
        }

        return $this->successResponse(data: $data);
    }

    public function listJenis(Request $request, $subject){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'indikator' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sdaService->getListJenis($this->idUsecase, $subject, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'indikator' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sdaService->getListJenis($idUsecase, $subject, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function listTahun(Request $request, $subject){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'indikator' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sdaService->getListTahun($this->idUsecase, $subject, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'indikator' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sdaService->getListTahun($idUsecase, $subject, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function periodeSda(Request $request, $subject){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'indikator' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sdaService->getPeriodeSda($this->idUsecase, $subject, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'indikator' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sdaService->getPeriodeSda($idUsecase, $subject, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function mapSda(Request $request, $subject){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'indikator' => 'required',
                'jenis' => 'required',
                'tahun' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sdaService->getMapSda($this->idUsecase, $subject, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'indikator' => 'required',
                'jenis' => 'required',
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sdaService->getMapSda($idUsecase, $subject, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function barSda(Request $request, $subject){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'indikator' => 'required',
                'jenis' => 'required',
                'tahun' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sdaService->getBarSda($this->idUsecase, $subject, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'indikator' => 'required',
                'jenis' => 'required',
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sdaService->getBarSda($idUsecase, $subject, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function areaSda(Request $request, $subject){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'indikator' => 'required',
                'jenis' => 'required',
                'periode' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sdaService->getAreaSda($this->idUsecase, $subject, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'indikator' => 'required',
                'jenis' => 'required',
                'periode' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            
            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sdaService->getAreaSda($idUsecase, $subject, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function detailSda(Request $request, $subject){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'indikator' => 'required',
                'jenis' => 'required',
                'periode' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sdaService->getDetailSda($this->idUsecase, $subject, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'indikator' => 'required',
                'jenis' => 'required',
                'periode' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sdaService->getDetailSda($idUsecase, $subject, $validatedData);
        }

        return $this->successResponse(data: $data);
    }
}

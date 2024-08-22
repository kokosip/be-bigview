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

    public function listIndikator($subject){
            $data = $this->sdaService->getListIndikator($this->idUsecase, $subject);

        return $this->successResponse(data: $data);
    }

    public function listJenis(Request $request, $subject){
        $validator = Validator::make($request->all(), [
            'indikator' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sdaService->getListJenis($this->idUsecase, $subject, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function listTahun(Request $request, $subject){
            $validator = Validator::make($request->all(), [
                'indikator' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sdaService->getListTahun($this->idUsecase, $subject, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function periodeSda(Request $request, $subject){
        $validator = Validator::make($request->all(), [
            'indikator' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sdaService->getPeriodeSda($this->idUsecase, $subject, $validator->validate());

        return $this->successResponse(data: $data);
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
        $data = $this->sdaService->getMapSda($this->idUsecase, $subject, $validator->validate());

        return $this->successResponse(data: $data);
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
        $data = $this->sdaService->getBarSda($this->idUsecase, $subject, $validator->validate());

        return $this->successResponse(data: $data);
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
        $data = $this->sdaService->getAreaSda($this->idUsecase, $subject, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function detailSda(Request $request, $subject){
        $validator = Validator::make($request->all(), [
            'indikator' => 'required',
            'jenis' => 'required',
            'periode' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sdaService->getDetailSda($this->idUsecase, $subject, $validator->validate());

        return $this->successResponse(data: $data);
    }
}

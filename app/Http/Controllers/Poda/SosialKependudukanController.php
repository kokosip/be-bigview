<?php

namespace App\Http\Controllers\Poda;

use App\Http\Controllers\Controller;
use App\Services\Poda\SosialKependudukanServices;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SosialKependudukanController extends Controller
{
    use ApiResponse;
    protected $sosialService;
    protected $idUsecase;
    protected $level;

    public function __construct(SosialKependudukanServices $sosialService)
    {
        $this->sosialService = $sosialService;
        $this->level = Auth::user()->level ?? null;
        $this->idUsecase = Auth::user()->id_usecase ?? null;
    }

    // Start Kependudukan
    public function tahunJumlahPenduduk(Request $request){
        if ($this->level >= 1) {
            $data = $this->sosialService->getTahunJumlahPenduduk($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getTahunJumlahPenduduk($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function mapJumlahPenduduk(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getMapJumlahPenduduk($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getMapJumlahPenduduk($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function pieJumlahPenduduk(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getPieJumlahPenduduk($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getPieJumlahPenduduk($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function barJumlahPenduduk(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getBarJumlahPenduduk($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getBarJumlahPenduduk($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function detailJumlahPenduduk(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getDetailJumlahPenduduk($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getDetailJumlahPenduduk($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }
    // End Kependudukan

    // Start Rentang Usia
    public function tahunRentangUsia(Request $request){
        if ($this->level >= 1) {
            $data = $this->sosialService->getTahunRentangUsia($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->sosialService->getTahunRentangUsia($validator->validate()['id_usecase']);
        }

        return $this->successResponse(data: $data);
    }

    public function stackedBarRentangUsia(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getStackedBarRentangUsia($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getStackedBarRentangUsia($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function detailRentangUsia(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getDetailRentangUsia($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getDetailRentangUsia($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }
    // End Rentang Usia

    // Start Laju Pertumbuhan
    public function periodeLaju(Request $request){
        if ($this->level >= 1) {
            $data = $this->sosialService->getPeriodeLaju($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->sosialService->getPeriodeLaju($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function namaDaerahLaju(Request $request){
        if ($this->level >= 1) {
            $data = $this->sosialService->getNamaDaerahLaju($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            } 

            $data = $this->sosialService->getNamaDaerahLaju($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function dualAxesLaju(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'nama_daerah' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getDualAxesLaju($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'nama_daerah' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getDualAxesLaju($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function detailLaju(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getDetailLaju($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getDetailLaju($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }
    // End Laju Pertumbuhan

    // Start Rasio Jenis Kelamin
    public function tahunRasio(Request $request){
        if ($this->level >= 1) {
            $data = $this->sosialService->getTahunRasio($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->sosialService->getTahunRasio($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function mapRasio(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getMapRasio($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getMapRasio($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function barRasio(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getBarRasio($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }   

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getBarRasio($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function detailRasio(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getDetailRasio($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getDetailRasio($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }
    // End Rasio Jenis Kelamin

    // Start Kepadatan Penduduk
    public function tahunKepadatan(Request $request){
        if ($this->level >= 1) {
            $data = $this->sosialService->getTahunKepadatan($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getTahunKepadatan($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function mapKepadatan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getMapKepadatan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getMapKepadatan($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function barKepadatan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getBarKepadatan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getBarKepadatan($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function detailKepadatan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getDetailKepadatan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getDetailKepadatan($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }
    // End Kepadatan Penduduk

    // Start IPM
    public function periodeIPM(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'filter' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getPeriodeIPM($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'filter' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getPeriodeIPM($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function namaDaerahIPM(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'filter' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getNamaDaerahIPM($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'filter' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getNamaDaerahIPM($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function indikatorIPM(Request $request){
        if ($this->level >= 1) {
            $data = $this->sosialService->getIndikatorIPM($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->sosialService->getIndikatorIPM($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function areaIPM(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'filter' => 'required',
                'nama_daerah' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getAreaIPM($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'filter' => 'required',
                'nama_daerah' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getAreaIPM($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function mapIPM(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'filter' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getMapIPM($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'filter' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getMapIPM($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function detailIPM(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'filter' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getDetailIPM($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'filter' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getDetailIPM($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }
    // End IPM

    // Start Kemiskinan
    public function indikatorKemiskinan(Request $request){
        if ($this->level >= 1) {
            $data = $this->sosialService->getIndikatorKemiskinan($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->sosialService->getIndikatorKemiskinan($validator->validate()['id_usecase']);
        }

        return $this->successResponse(data: $data);
    }

    public function tahunKemiskinan(Request $request){
        if ($this->level >= 1) {
            $data = $this->sosialService->getTahunKemiskinan($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->sosialService->getTahunKemiskinan($validator->validate()['id_usecase']);
        }

        return $this->successResponse(data: $data);
    }

    public function daerahKemiskinan(Request $request){
        if ($this->level >= 1) {
            $data = $this->sosialService->getDaerahKemiskinan($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->sosialService->getDaerahKemiskinan($validator->validate()['id_usecase']);
        }

        return $this->successResponse(data: $data);
    }

    public function periodeKemiskinan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'filter' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getPeriodeKemiskinan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'filter' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getPeriodeKemiskinan($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function mapKemiskinan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'filter' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getMapKemiskinan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'filter' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getMapKemiskinan($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function areaKemiskinan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'filter' => 'required',
                'nama_daerah' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getAreaKemiskinan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'filter' => 'required',
                'nama_daerah' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getAreaKemiskinan($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function detailKemiskinan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'filter' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getDetailKemiskinan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'filter' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getDetailKemiskinan($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }
    // End Kemiskinan

    // Start Pekerjaan
    public function indikatorPekerjaan(Request $request){
        if ($this->level >= 1) {
            $data = $this->sosialService->getIndikatorPekerjaan($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->sosialService->getIndikatorPekerjaan($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function tahunPekerjaan(Request $request){
        if ($this->level >= 1) {
            $data = $this->sosialService->getTahunPekerjaan($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->sosialService->getTahunPekerjaan($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function tahunJenisPekerjaan(Request $request){
        if ($this->level >= 1) {
            $data = $this->sosialService->getTahunJenisPekerjaan($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->sosialService->getTahunJenisPekerjaan($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function periodePekerjaan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'filter' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getPeriodePekerjaan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'filter' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getPeriodePekerjaan($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function barJenisPekerjaan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getBarJenisPekerjaan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getBarJenisPekerjaan($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function mapPekerjaan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'filter' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getMapPekerjaan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'filter' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getMapPekerjaan($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function linePekerjaan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'filter' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getLinePekerjaan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'filter' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getLinePekerjaan($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function detailJenisPekerjaan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getDetailJenisPekerjaan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getDetailJenisPekerjaan($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function detailPekerjaan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'indikator' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getDetailPekerjaan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'indikator' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getDetailPekerjaan($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }
    // End Pekerjaan

    // Start Pendidikan
    public function tahunAjaranPendidikan(Request $request){
        if ($this->level >= 1) {
            $data = $this->sosialService->getTahunAjaranPendidikan($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->sosialService->getTahunAjaranPendidikan($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function tahunPendidikan(Request $request){
        if ($this->level >= 1) {
            $data = $this->sosialService->getTahunPendidikan($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->sosialService->getTahunPendidikan($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function jenjangPendidikan(Request $request){
        if ($this->level >= 1) {
            $data = $this->sosialService->getJenjangPendidikan($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->sosialService->getJenjangPendidikan($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function indikatorPendidikan(Request $request){
        if ($this->level >= 1) {
            $data = $this->sosialService->getIndikatorPendidikan($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->sosialService->getIndikatorPendidikan($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function barPendidikan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'jenjang' => 'required',
                'indikator' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getBarPendidikan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'jenjang' => 'required',
                'indikator' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getBarPendidikan($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function barJenjangPendidikan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->sosialService->getBarJenjangPendidikan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(), [
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->sosialService->getBarJenjangPendidikan($idUsecase, $validatedData);
        }

        return $this->successResponse(data: $data);
    }

    public function mapPendidikan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'jenjang' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getMapPendidikan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function detailPendidikan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getDetailPendidikan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }
    // End Pendidikan

    // Start Kesehatan
    public function tahunKesehatan(){
        $data = $this->sosialService->getTahunKesehatan($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function indikatorKesehatan(){
        $data = $this->sosialService->getIndikatorKesehatan($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function periodeKesehatan(Request $request){
        $data = $this->sosialService->getPeriodeKesehatan($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function barKesehatan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'filter' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getBarKesehatan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function barColumnKesehatan(Request $request){
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getBarColumnKesehatan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function mapKesehatan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getMapKesehatan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function detailKesehatan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getDetailKesehatan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }
    // End Kesehatan
}

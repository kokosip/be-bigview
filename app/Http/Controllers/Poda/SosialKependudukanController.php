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

    public function __construct(SosialKependudukanServices $sosialService)
    {
        $this->sosialService = $sosialService;
        $this->idUsecase = Auth::user()->id_usecase;
    }

    // Start Kependudukan
    public function tahunJumlahPenduduk(){
        try{
            $data = $this->sosialService->getTahunJumlahPenduduk($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function mapJumlahPenduduk(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getMapJumlahPenduduk($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function pieJumlahPenduduk(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getPieJumlahPenduduk($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function barJumlahPenduduk(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getBarJumlahPenduduk($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function detailJumlahPenduduk(Request $request){
        $tahun = $request->input("tahun");

        try{
            $data = $this->sosialService->getDetailJumlahPenduduk($this->idUsecase, $tahun);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End Kependudukan

    // Start Rentang Usia
    public function tahunRentangUsia(){
        try{
            $data = $this->sosialService->getTahunRentangUsia($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function stackedBarRentangUsia(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getStackedBarRentangUsia($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End Rentang Usia

    // Start Rasio Jenis Kelamin
    public function tahunRasio(){
        try{
            $data = $this->sosialService->getTahunRasio($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function mapRasio(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getMapRasio($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function barRasio(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getBarRasio($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End Rasio Jenis Kelamin

    // Start Kepadatan Penduduk
    public function tahunKepadatan(){
        try{
            $data = $this->sosialService->getTahunKepadatan($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function mapKepadatan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getMapKepadatan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function barKepadatan(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getBarKepadatan($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End Kepadatan Penduduk

    // Start IPM
    public function periodeIPM(Request $request){
        $validator = Validator::make($request->all(), [
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getPeriodeIPM($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function namaDaerahIPM(Request $request){
        $validator = Validator::make($request->all(), [
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getNamaDaerahIPM($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function indikatorIPM(){
        try{
            $data = $this->sosialService->getIndikatorIPM($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function areaIPM(Request $request){
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
            'filter' => 'required',
            'nama_daerah' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getAreaIPM($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function mapIPM(Request $request){
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->sosialService->getMapIPM($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End IPM

    // Start Kemiskinan
    public function tahunKemiskinan(){
        try{
            $data = $this->sosialService->getTahunKemiskinan($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
}

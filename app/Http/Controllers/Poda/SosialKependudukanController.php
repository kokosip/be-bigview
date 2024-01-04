<?php

namespace App\Http\Controllers\Poda;

use App\Http\Controllers\Controller;
use App\Services\Poda\SosialKependudukanServices;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $tahun = $request->input("tahun");

        try{
            $data = $this->sosialService->getMapJumlahPenduduk($tahun, $this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function pieJumlahPenduduk(Request $request){
        $tahun = $request->input("tahun");

        try{
            $data = $this->sosialService->getPieJumlahPenduduk($tahun, $this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function barJumlahPenduduk(Request $request){
        $tahun = $request->input("tahun");

        try{
            $data = $this->sosialService->getBarJumlahPenduduk($tahun, $this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function detailJumlahPenduduk(Request $request){
        $tahun = $request->input("tahun");

        try{
            $data = $this->sosialService->getDetailJumlahPenduduk($tahun, $this->idUsecase);

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
        $tahun = $request->input('tahun');
        try{
            $data = $this->sosialService->getStackedBarRentangUsia($tahun, $this->idUsecase);

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
        $tahun = $request->input('tahun');

        try{
            $data = $this->sosialService->getMapRasio($tahun, $this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function barRasio(Request $request){
        $tahun = $request->input('tahun');

        try{
            $data = $this->sosialService->getBarRasio($tahun, $this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End Rasio Jenis Kelamin

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

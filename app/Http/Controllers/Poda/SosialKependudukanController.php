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
}

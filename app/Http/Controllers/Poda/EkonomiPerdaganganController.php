<?php

namespace App\Http\Controllers\Poda;

use App\Http\Controllers\Controller;
use App\Services\Poda\EkonomiPerdaganganServices;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EkonomiPerdaganganController extends Controller
{
    use ApiResponse;
    protected $ekonomiService;
    protected $idUsecase;

    public function __construct(EkonomiPerdaganganServices $ekonomiService)
    {
        $this->ekonomiService = $ekonomiService;
        $this->idUsecase = Auth::user()->id_usecase;
    }

    // Inflasi dan IHK
    public function monthPeriodeInflasi(){
        try{
            $data = $this->ekonomiService->getMonthPeriodeInflasi($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function namaDaerahInflasi(){
        try{
            $data = $this->ekonomiService->getNamaDaerahInflasi($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function tahunInflasi(){
        try{
            $data = $this->ekonomiService->getTahunInflasi($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function bulanInflasi(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getBulanInflasi($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function mapInflasi(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required',
            'bulan' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getMapInflasi($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function dualChartInflasi(Request $request){
        $validator = Validator::make($request->all(),[
            'nama_daerah' => 'required',
            'periode' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getDualChartInflasi($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
    // End Infalsi dan IHK

    // Start PDRB
    public function tahunPDRB(){
        try{
            $data = $this->ekonomiService->getTahunPDRB($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function kategoriPDRB(){
        try{
            $data = $this->ekonomiService->getKategoriPDRB($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function sektorPDRB(){
        try{
            $data = $this->ekonomiService->getSektorPDRB($this->idUsecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function cardPDRB(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required',
            'filter' => 'required',
            'jenis' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getCardPDRB($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function barPDRB(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required',
            'filter' => 'required',
            'jenis' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->ekonomiService->getBarPDRB($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
}

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
    protected $level;

    public function __construct(EkonomiPerdaganganServices $ekonomiService)
    {
        $this->ekonomiService = $ekonomiService;
        $this->level = Auth::user()->level ?? null;
        $this->idUsecase = Auth::user()->id_usecase ?? null;
    }

    // Start Ekonomi PAD
    public function areaPad(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getAreaPad($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->ekonomiService->getAreaPad($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function detailPad(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getDetailPad($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getDetailPad($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }
    // End Ekonomi PAD

    // Start Trend Perdagangan
    public function periodeTrendPerdagangan(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getPeriodeTrendPerdagangan($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(), [
                'id_usecase' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getPeriodeTrendPerdagangan($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function areaTrendPerdagangan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'periode' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getAreaTrendPerdagangan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'periode' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getAreaTrendPerdagangan($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function detailTrendPerdagangan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'periode' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getDetailTrendPerdagangan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'periode' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getDetailTrendPerdagangan($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }
    // End Perdagangan

    // Start Top Komoditas
    public function tahunKomoditas(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getTahunKomoditas($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->ekonomiService->getTahunKomoditas($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function barKomoditas(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getBarKomoditas($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getBarKomoditas($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function detailKomoditas(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getDetailKomoditas($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getDetailKomoditas($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }
    // End Top Komoditas

    // Start Top Pad KabKota
    public function tahunPadKabKota(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getTahunPadKabKota($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->ekonomiService->getTahunPadKabKota($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function barPadKabKota(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getBarPadKabKota($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getBarPadKabKota($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function detailPadKabKota(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getDetailPadKabKota($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getDetailPadKabKota($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }
    // End Top Komoditas

    // Start Inflasi dan IHK
    public function monthPeriodeInflasi(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getMonthPeriodeInflasi($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->ekonomiService->getMonthPeriodeInflasi($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function namaDaerahInflasi(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getNamaDaerahInflasi($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->ekonomiService->getNamaDaerahInflasi($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function tahunInflasi(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getTahunInflasi($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->ekonomiService->getTahunInflasi($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function bulanInflasi(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getBulanInflasi($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getBulanInflasi($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function mapInflasi(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'bulan' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getMapInflasi($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'bulan' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getMapInflasi($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function dualChartInflasi(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'nama_daerah' => 'required',
                'periode' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getDualChartInflasi($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'nama_daerah' => 'required',
                'periode' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getDualChartInflasi($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function detailInflasi(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getDetailInflasi($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getDetailInflasi($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }
    // End Infalsi dan IHK

    // Start PDRB
    public function tahunPDRB(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getTahunPDRB($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getTahunPDRB($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function kategoriPDRB(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getKategoriPDRB($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->ekonomiService->getKategoriPDRB($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function sektorPDRB(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getSektorPDRB($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->ekonomiService->getSektorPDRB($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function cardPDRB(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'filter' => 'required',
                'jenis' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getCardPDRB($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'filter' => 'required',
                'jenis' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getCardPDRB($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function barPDRB(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'filter' => 'required',
                'jenis' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getBarPDRB($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'filter' => 'required',
                'jenis' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getBarPDRB($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function areaPDRB(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'filter' => 'required',
                'jenis' => 'required',
                'sektor' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getAreaPDRB($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'filter' => 'required',
                'jenis' => 'required',
                'sektor' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getAreaPDRB($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function detailPDRB(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'filter' => 'required',
                'jenis' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getDetailPDRB($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'filter' => 'required',
                'jenis' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getDetailPDRB($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }
    // End PDRB

    // Start Pariwisata
    public function indikatorPariwisata(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getIndikatorPariwisata($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->ekonomiService->getIndikatorPariwisata($validator->validate()['id_usecase']);
        }   
        return $this->successResponse(data: $data);
    }

    public function namaDaerahPariwisataDTW(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getNamaDaerahPariwisataDTW($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->ekonomiService->getNamaDaerahPariwisataDTW($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function periodePariwisataDTW(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getPeriodePariwisataDTW($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->ekonomiService->getPeriodePariwisataDTW($validator->validate()['id_usecase']);
        }
        
        return $this->successResponse(data: $data);
    }

    public function tahunPariwisataDTW(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getTahunPariwisataDTW($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->ekonomiService->getTahunPariwisataDTW($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function mapPariwisataDTW(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getMapPariwisataDTW($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getMapPariwisataDTW($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function linePariwisataDTW(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'periode' => 'required',
                'daerah' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getLinePariwisataDTW($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'periode' => 'required',
                'daerah' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getLinePariwisataDTW($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function detailPariwisataDTW(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'periode' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getDetailPariwisataDTW($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'periode' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getDetailPariwisataDTW($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function periodePariwisataHotel(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getPeriodePariwisataHotel($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->ekonomiService->getPeriodePariwisataHotel($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function tahunPariwisataHotel(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getTahunPariwisataHotel($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->ekonomiService->getTahunPariwisataHotel($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function mapPariwisataHotel(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getMapPariwisataHotel($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getMapPariwisataHotel($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function barPariwisataHotel(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getBarPariwisataHotel($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);
            
            $data = $this->ekonomiService->getBarPariwisataHotel($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function linePariwisataHotel(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'periode' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getLinePariwisataHotel($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'periode' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getLinePariwisataHotel($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function detailPariwisataHotel(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getDetailPariwisataHotel($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getDetailPariwisataHotel($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function periodePariwisataWisatawan(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getPeriodePariwisataWisatawan($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->ekonomiService->getPeriodePariwisataWisatawan($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function cardPariwisataWisatawan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'periode' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getCardPariwisataWisatawan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'periode' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getCardPariwisataWisatawan($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function linePariwisataWisatawan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'periode' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getLinePariwisataWisatawan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'periode' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getLinePariwisataWisatawan($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function detailPariwisataWisatawan(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'periode' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getDetailPariwisataWisatawan($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'periode' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            
            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getDetailPariwisataWisatawan($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function tahunPariwisataTPK(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getTahunPariwisataTPK($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getTahunPariwisataTPK($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function bulanPariwisataTPK(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getBulanPariwisataTPK($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getBulanPariwisataTPK($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function cardPariwisataTPK(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'bulan' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getCardPariwisataTPK($this->idUsecase, $validator->validate());
            return $this->successResponse(data: $data);
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'bulan' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getCardPariwisataTPK($idUsecase, $validatedData);
            return $this->successResponse(data: $data);
        }
    }

    public function linePariwisataTPK(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getLinePariwisataTPK($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getLinePariwisataTPK($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function detailPariwisataTPK(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getDetailPariwisataTPK($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);
            
            $data = $this->ekonomiService->getDetailPariwisataTPK($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function periodePariwisataResto(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getPeriodePariwisataResto($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->ekonomiService->getPeriodePariwisataResto($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function tahunPariwisataResto(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getTahunPariwisataResto($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->ekonomiService->getTahunPariwisataResto($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function namaDaerahPariwisataResto(Request $request){
        if ($this->level >= 1) {
            $data = $this->ekonomiService->getNamaDaerahPariwisataResto($this->idUsecase);
        } else {
            $validator = Validator::make($request->all(),[
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $data = $this->ekonomiService->getNamaDaerahPariwisataResto($validator->validate()['id_usecase']);
        }
        return $this->successResponse(data: $data);
    }

    public function mapPariwisataResto(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getMapPariwisataResto($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'tahun' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getMapPariwisataResto($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function linePariwisataResto(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'periode' => 'required',
                'daerah' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getLinePariwisataResto($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'periode' => 'required',
                'daerah' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);

            $data = $this->ekonomiService->getLinePariwisataResto($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }

    public function detailPariwisataResto(Request $request){
        if ($this->level >= 1) {
            $validator = Validator::make($request->all(),[
                'periode' => 'required',
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }
            $data = $this->ekonomiService->getDetailPariwisataResto($this->idUsecase, $validator->validate());
        } else {
            $validator = Validator::make($request->all(),[
                'periode' => 'required',
                'id_usecase' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->validationResponse($validator);
            }

            $validatedData = $validator->validated();
            $idUsecase = $validatedData['id_usecase'];
            unset($validatedData['id_usecase']);


            $data = $this->ekonomiService->getDetailPariwisataResto($idUsecase, $validatedData);
        }
        return $this->successResponse(data: $data);
    }
}

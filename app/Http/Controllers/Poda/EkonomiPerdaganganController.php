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
        $this->idUsecase = Auth::user()->id_usecase ?? null;
    }

    // Start Ekonomi PAD
    public function areaPad(){
        $data = $this->ekonomiService->getAreaPad($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function detailPad(){
        $data = $this->ekonomiService->getDetailPad($this->idUsecase);
        return $this->successResponse(data: $data);
    }
    // End Ekonomi PAD

    // Start Trend Perdagangan
    public function periodeTrendPerdagangan(){
        $data = $this->ekonomiService->getPeriodeTrendPerdagangan($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function areaTrendPerdagangan(Request $request){
        $validator = Validator::make($request->all(),[
            'periode' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getAreaTrendPerdagangan($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function detailTrendPerdagangan(Request $request){
        $validator = Validator::make($request->all(),[
            'periode' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getDetailTrendPerdagangan($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }
    // End Perdagangan

    // Start Top Komoditas
    public function tahunKomoditas(){
        $data = $this->ekonomiService->getTahunKomoditas($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function barKomoditas(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getBarKomoditas($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function detailKomoditas(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getDetailKomoditas($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }
    // End Top Komoditas

    // Start Top Pad KabKota
    public function tahunPadKabKota(){
        $data = $this->ekonomiService->getTahunPadKabKota($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function barPadKabKota(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getBarPadKabKota($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function detailPadKabKota(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getDetailPadKabKota($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }
    // End Top Komoditas

    // Start Inflasi dan IHK
    public function monthPeriodeInflasi(){
        $data = $this->ekonomiService->getMonthPeriodeInflasi($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function namaDaerahInflasi(){
        $data = $this->ekonomiService->getNamaDaerahInflasi($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function tahunInflasi(){
        $data = $this->ekonomiService->getTahunInflasi($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function bulanInflasi(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getBulanInflasi($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function mapInflasi(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required',
            'bulan' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
            $data = $this->ekonomiService->getMapInflasi($this->idUsecase, $validator->validate());
            return $this->successResponse(data: $data);
    }

    public function dualChartInflasi(Request $request){
        $validator = Validator::make($request->all(),[
            'nama_daerah' => 'required',
            'periode' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getDualChartInflasi($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function detailInflasi(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
            $data = $this->ekonomiService->getDetailInflasi($this->idUsecase, $validator->validate());
            return $this->successResponse(data: $data);
    }
    // End Infalsi dan IHK

    // Start PDRB
    public function tahunPDRB(){
        $data = $this->ekonomiService->getTahunPDRB($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function kategoriPDRB(){
        $data = $this->ekonomiService->getKategoriPDRB($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function sektorPDRB(){
        $data = $this->ekonomiService->getSektorPDRB($this->idUsecase);
        return $this->successResponse(data: $data);
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
        $data = $this->ekonomiService->getCardPDRB($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
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
        $data = $this->ekonomiService->getBarPDRB($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function areaPDRB(Request $request){
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
        return $this->successResponse(data: $data);
    }

    public function detailPDRB(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required',
            'filter' => 'required',
            'jenis' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getDetailPDRB($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }
    // End PDRB

    // Start Pariwisata
    public function indikatorPariwisata(){
        $data = $this->ekonomiService->getIndikatorPariwisata($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function namaDaerahPariwisataDTW(){
        $data = $this->ekonomiService->getNamaDaerahPariwisataDTW($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function periodePariwisataDTW(){
        $data = $this->ekonomiService->getPeriodePariwisataDTW($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function tahunPariwisataDTW(){
        $data = $this->ekonomiService->getTahunPariwisataDTW($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function mapPariwisataDTW(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getMapPariwisataDTW($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function linePariwisataDTW(Request $request){
        $validator = Validator::make($request->all(),[
            'periode' => 'required',
            'daerah' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getLinePariwisataDTW($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function detailPariwisataDTW(Request $request){
        $validator = Validator::make($request->all(),[
            'periode' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getDetailPariwisataDTW($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function periodePariwisataHotel(){
        $data = $this->ekonomiService->getPeriodePariwisataHotel($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function tahunPariwisataHotel(){
        $data = $this->ekonomiService->getTahunPariwisataHotel($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function mapPariwisataHotel(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getMapPariwisataHotel($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function barPariwisataHotel(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getBarPariwisataHotel($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function linePariwisataHotel(Request $request){
        $validator = Validator::make($request->all(),[
            'periode' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getLinePariwisataHotel($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function detailPariwisataHotel(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getDetailPariwisataHotel($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function periodePariwisataWisatawan(){
        $data = $this->ekonomiService->getPeriodePariwisataWisatawan($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function cardPariwisataWisatawan(Request $request){
        $validator = Validator::make($request->all(),[
            'periode' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getCardPariwisataWisatawan($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function linePariwisataWisatawan(Request $request){
        $validator = Validator::make($request->all(),[
            'periode' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getLinePariwisataWisatawan($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function detailPariwisataWisatawan(Request $request){
        $validator = Validator::make($request->all(),[
            'periode' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getDetailPariwisataWisatawan($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function tahunPariwisataTPK(){
        $data = $this->ekonomiService->getTahunPariwisataTPK($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function bulanPariwisataTPK(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getBulanPariwisataTPK($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function cardPariwisataTPK(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required',
            'bulan' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getCardPariwisataTPK($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function linePariwisataTPK(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getLinePariwisataTPK($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function detailPariwisataTPK(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getDetailPariwisataTPK($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function periodePariwisataResto(){
        $data = $this->ekonomiService->getPeriodePariwisataResto($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function tahunPariwisataResto(){
        $data = $this->ekonomiService->getTahunPariwisataResto($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function namaDaerahPariwisataResto(){
        $data = $this->ekonomiService->getNamaDaerahPariwisataResto($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function mapPariwisataResto(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getMapPariwisataResto($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function linePariwisataResto(Request $request){
        $validator = Validator::make($request->all(),[
            'periode' => 'required',
            'daerah' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getLinePariwisataResto($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function detailPariwisataResto(Request $request){
        $validator = Validator::make($request->all(),[
            'periode' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->ekonomiService->getDetailPariwisataResto($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }
}

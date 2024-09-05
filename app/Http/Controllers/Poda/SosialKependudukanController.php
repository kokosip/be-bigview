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
        $this->idUsecase = Auth::user()->id_usecase ?? null;
    }

    // Start Kependudukan
    public function tahunJumlahPenduduk()
    {
        $data = $this->sosialService->getTahunJumlahPenduduk($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function mapJumlahPenduduk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getMapJumlahPenduduk($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function pieJumlahPenduduk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getPieJumlahPenduduk($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function barJumlahPenduduk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getBarJumlahPenduduk($this->idUsecase, $validator->validate());
        return $this->successResponse(data: $data);
    }

    public function detailJumlahPenduduk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getDetailJumlahPenduduk($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }
    // End Kependudukan

    // Start Rentang Usia
    public function tahunRentangUsia()
    {
        $data = $this->sosialService->getTahunRentangUsia($this->idUsecase);

        return $this->successResponse(data: $data);
    }

    public function stackedBarRentangUsia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getStackedBarRentangUsia($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function detailRentangUsia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getDetailRentangUsia($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }
    // End Rentang Usia

    // Start Laju Pertumbuhan
    public function periodeLaju()
    {
        $data = $this->sosialService->getPeriodeLaju($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function namaDaerahLaju()
    {
        $data = $this->sosialService->getNamaDaerahLaju($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function dualAxesLaju(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
            'nama_daerah' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getDualAxesLaju($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function detailLaju(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getDetailLaju($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }
    // End Laju Pertumbuhan

    // Start Rasio Jenis Kelamin
    public function tahunRasio()
    {
        $data = $this->sosialService->getTahunRasio($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function mapRasio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getMapRasio($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function barRasio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getBarRasio($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function detailRasio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getDetailRasio($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }
    // End Rasio Jenis Kelamin

    // Start Kepadatan Penduduk
    public function tahunKepadatan()
    {
        $data = $this->sosialService->getTahunKepadatan($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function mapKepadatan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getMapKepadatan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function barKepadatan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getBarKepadatan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function detailKepadatan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getDetailKepadatan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }
    // End Kepadatan Penduduk

    // Start IPM
    public function periodeIPM(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getPeriodeIPM($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function namaDaerahIPM(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getNamaDaerahIPM($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function indikatorIPM()
    {
        $data = $this->sosialService->getIndikatorIPM($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function areaIPM(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
            'filter' => 'required',
            'nama_daerah' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getAreaIPM($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function mapIPM(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getMapIPM($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function detailIPM(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getDetailIPM($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }
    // End IPM

    // Start Kemiskinan
    public function indikatorKemiskinan()
    {
        $data = $this->sosialService->getIndikatorKemiskinan($this->idUsecase);

        return $this->successResponse(data: $data);
    }

    public function tahunKemiskinan()
    {
        $data = $this->sosialService->getTahunKemiskinan($this->idUsecase);

        return $this->successResponse(data: $data);
    }

    public function daerahKemiskinan()
    {
        $data = $this->sosialService->getDaerahKemiskinan($this->idUsecase);

        return $this->successResponse(data: $data);
    }

    public function periodeKemiskinan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getPeriodeKemiskinan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function mapKemiskinan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getMapKemiskinan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function areaKemiskinan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
            'filter' => 'required',
            'nama_daerah' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getAreaKemiskinan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function detailKemiskinan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getDetailKemiskinan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }
    // End Kemiskinan

    // Start Pekerjaan
    public function indikatorPekerjaan()
    {
        $data = $this->sosialService->getIndikatorPekerjaan($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function tahunPekerjaan()
    {
        $data = $this->sosialService->getTahunPekerjaan($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function tahunJenisPekerjaan()
    {
        $data = $this->sosialService->getTahunJenisPekerjaan($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function periodePekerjaan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getPeriodePekerjaan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function barJenisPekerjaan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getBarJenisPekerjaan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function mapPekerjaan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getMapPekerjaan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function linePekerjaan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
            'filter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getLinePekerjaan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function detailJenisPekerjaan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getDetailJenisPekerjaan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function detailPekerjaan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
            'indikator' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getDetailPekerjaan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }
    // End Pekerjaan

    // Start Pendidikan
    public function tahunAjaranPendidikan()
    {
        $data = $this->sosialService->getTahunAjaranPendidikan($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function tahunPendidikan()
    {
        $data = $this->sosialService->getTahunPendidikan($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function jenjangPendidikan()
    {
        $data = $this->sosialService->getJenjangPendidikan($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function indikatorPendidikan()
    {
        $data = $this->sosialService->getIndikatorPendidikan($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function barPendidikan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'jenjang' => 'required',
            'indikator' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getBarPendidikan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function barJenjangPendidikan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getBarJenjangPendidikan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function mapPendidikan(Request $request)
    {
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

    public function detailPendidikan(Request $request)
    {
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
    public function tahunKesehatan()
    {
        $data = $this->sosialService->getTahunKesehatan($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function indikatorKesehatan()
    {
        $data = $this->sosialService->getIndikatorKesehatan($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function periodeKesehatan()
    {
        $data = $this->sosialService->getPeriodeKesehatan($this->idUsecase);
        return $this->successResponse(data: $data);
    }

    public function barKesehatan(Request $request)
    {
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

    public function barColumnKesehatan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'periode' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getBarColumnKesehatan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function mapKesehatan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        $data = $this->sosialService->getMapKesehatan($this->idUsecase, $validator->validate());

        return $this->successResponse(data: $data);
    }

    public function detailKesehatan(Request $request)
    {
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

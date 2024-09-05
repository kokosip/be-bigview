<?php

namespace App\Services\Poda;

use App\Repositories\Admin\MasterRepositories;
use App\Repositories\Poda\SumberDayaAlamRepositories;
use App\Traits\FormatChart;
use App\Exceptions\ErrorResponse;

class SumberDayaAlamServices
{

    use FormatChart;
    protected $sdaRepositories;
    protected $masterRepositories;

    public function __construct(SumberDayaAlamRepositories $sdaRepositories, MasterRepositories $masterRepositories)
    {
        $this->sdaRepositories = $sdaRepositories;
        $this->masterRepositories = $masterRepositories;
    }

    public function getSubjectName($subject)
    {
        if ($subject == 'pertanian') {
            return 'Tanaman Pangan';
        } else {
            return ucfirst($subject);
        }
    }

    public function getListIndikator($idUsecase, $subject)
    {
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sdaRepositories->getListIndikator($idUsecase, $this->getSubjectName($subject));

        $response = $this->listIndikator($rows);

        return $response;
    }

    public function getListJenis($idUsecase, $subject, $indikator)
    {
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sdaRepositories->getListJenis($idUsecase, $this->getSubjectName($subject), $indikator);

        $response = $this->listIndikator($rows);

        return $response;
    }

    public function getListTahun($idUsecase, $subject, $indikator)
    {
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sdaRepositories->getListTahun($idUsecase, $this->getSubjectName($subject), $indikator);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getPeriodeSda($idUsecase, $subject, $indikator)
    {
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sdaRepositories->getPeriodeSda($idUsecase, $this->getSubjectName($subject), $indikator);

        $response = $this->filterPeriode($rows);

        return $response;
    }

    public function getMapSda($idUsecase, $subject, $params)
    {
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sdaRepositories->getMapSda($idUsecase, $this->getSubjectName($subject), $params);

        $response = $this->mapLeaflet($rows);

        return $response;
    }

    public function getBarSda($idUsecase, $subject, $params)
    {
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sdaRepositories->getBarSda($idUsecase, $this->getSubjectName($subject), $params);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);

        $chart_params = [
            'y_axis_title' => $params['indikator'],
        ];

        $response = $this->barChart($rows, $kode_kabkota->kode_kab_kota, $chart_params);

        return $response;
    }

    public function getAreaSda($idUsecase, $subject, $params)
    {
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sdaRepositories->getAreaSda($idUsecase, $this->getSubjectName($subject), $params);

        if ($subject != 'perikanan') {
            $y_axis_title =  $params['indikator'] . " " . $params['jenis'];
        } else {
            $y_axis_title =  $params['jenis'];
        }

        $axis_title = [
            'y_axis_title' => $y_axis_title,
            'x_axis_title' => 'Tahun'
        ];

        $response = $this->areaLineChart($rows, $params, $axis_title, "chart_area");

        return $response;
    }

    public function getDetailSda($idUsecase, $subject, $params)
    {
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sdaRepositories->getDetailSda($idUsecase, $this->getSubjectName($subject), $params);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);

        $title = "Detail " . $params['indikator'] . " " . $params['jenis'] . ", " . $params['periode'];

        $response = $this->detailTable($rows, $kode_kabkota->kode_kab_kota, $title);

        return $response;
    }
}

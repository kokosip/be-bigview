<?php

namespace App\Services\Poda;

use App\Repositories\Admin\MasterRepositories;
use App\Repositories\Poda\SumberDayaAlamRepositories;
use App\Traits\FormatChart;
use Exception;

class SumberDayaAlamServices {

    use FormatChart;
    protected $sdaRepositories;
    protected $masterRepositories;

    public function __construct(SumberDayaAlamRepositories $sdaRepositories, MasterRepositories $masterRepositories)
    {
        $this->sdaRepositories = $sdaRepositories;
        $this->masterRepositories = $masterRepositories;
    }

}

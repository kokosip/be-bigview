<?php
namespace App\Services\Admin;

use App\Repositories\Admin\MenuRepositories;

class MenuServices {
    protected $menuRepositories;

    public function __construct(MenuRepositories $menuRepositories)
    {
        $this->menuRepositories = $menuRepositories;
    }

    public function insertMenu($data) {
        $latestSort = $this->menuRepositories->getLatestSort($data);

        $data['sort'] = $latestSort;
        $this->menuRepositories->insertMenu($data);

        return $data;
    }
}

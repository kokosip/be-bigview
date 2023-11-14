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

    public function getMenuUtama(){
        $rows = $this->menuRepositories->getMenuUtama();

        $result = $rows->pluck("name_menu")->values();

        return ["name_menu" => $result];
    }

    public function getListMenu($search, $perPage){
        $rows = $this->menuRepositories->getListMenu($search, $perPage);

        $pagination = [
            "current_page" => $rows->currentPage(),
            "per_page" => $rows->perPage(),
            "total_page" => ceil($rows->total() / $rows->perPage()),
            "total_row" => $rows->total(),
        ];

        return [
            $rows->items(),
            $pagination
        ];
    }
}

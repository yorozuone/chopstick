<?php
namespace app\controller\admin\page;

use \core\url;
use \core\response;

use \app\model\controller\admin\page\sort as dataset_page;

class sort extends \app\controller_admin
{
    // ********************************************************************************
    // **** 既定の処理
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $dataset_page = new dataset_page;
        $dataset_page->post();
        $dataset_page->update();
    }
}

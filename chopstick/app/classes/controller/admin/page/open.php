<?php
namespace app\controller\admin\page;

use \core\url;
use \core\response;

use \app\model\helper\page as dset_page;

class open extends \app\controller_admin
{
    // ********************************************************************************
    // **** 既定の処理
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $page_id = isset($params[0]) ? $params[0] : 0;
        $dset_page = new dset_page();
        response::redirect(url::create($dset_page->get_permanent_name_from_page_id($page_id)));
    }
}

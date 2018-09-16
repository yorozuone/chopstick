<?php
namespace app\controller\admin;

use core\db;

class menu extends \app\controller_admin
{
    // --------------------------------------------------------------------------------
    // 表示用のフォルダ名取得
    // --------------------------------------------------------------------------------
    public function action_index()
    {
        $vars = array
        (
            'rs_page_disabled' => \app\model\controller\admin\menu::fetch_disabled_page(),
        );
        echo $this->render('controller/admin/menu/index.twig', $vars);
    }
}
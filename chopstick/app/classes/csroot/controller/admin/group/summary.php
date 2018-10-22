<?php
namespace app\csroot\controller\admin\group;

use \core\db;
use \core\response;
use \core\url;

// cs_group
use \app\csroot\model\controller\admin\group\summary as rs_group;

class summary extends \app\controller_admin
{
    private $rs_group;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        $this->rs_group = new rs_group;
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index()
    {
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 並べ替えアクション
    // --------------------------------------------------------------------------------
    public function action_sort($params)
    {
        $this->rs_group->sort($params);
    }
    // ********************************************************************************
    // **** 表示
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    private function display()
    {
        $vars = array
        (
            'rs_group'              => $this->rs_group->fetch_all(),
        );
        echo $this->render('cscms/controller/admin/group/summary.twig', $vars);
    }
}
<?php
namespace app\cscms\controller\admin\stack;

use \core\db;
use \core\response;
use \core\url;

// cs_stack
use \app\cscms\model\controller\admin\stack\summary as rs_stack;

class summary extends \app\controller_admin
{
    private $stackgroup_id;
    private $rs_stack;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        $this->rs_stack = new rs_stack;
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->stackgroup_id = isset($params[0]) ? $params[0] : -1;
        $this->rs_stack->set_value('stackgroup_id', $this->stackgroup_id);
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 並べ替えアクション
    // --------------------------------------------------------------------------------
    public function action_sort($params)
    {
        $this->sort($params);
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
            'stackgroup_id' => $this->stackgroup_id,
            'rs_stack'    => $this->rs_stack->fetch_all(),
        );
        echo $this->render('cscms/controller/admin/stack/summary.twig', $vars);
    }
}

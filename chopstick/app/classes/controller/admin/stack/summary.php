<?php
namespace app\controller\admin\stack;

use \core\db;
use \core\response;
use \core\url;

// cs_stack
use \app\model\controller\admin\stack\summary as drec_stack;

class summary extends \app\controller_admin
{
    private $stackgroup_id;
    private $drec_stack;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        $this->drec_stack = new drec_stack;
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->stackgroup_id = isset($params[0]) ? $params[0] : -1;
        $this->drec_stack->set_value('stackgroup_id', $this->stackgroup_id);
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
            'drec_stack'    => $this->drec_stack->fetch_all(),
        );
        echo $this->render('controller/admin/stack/summary.twig', $vars);
    }
}

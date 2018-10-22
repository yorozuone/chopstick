<?php
namespace app\cspman\controller\project_task;

use \core\db;
use \core\response;
use \core\url;

use \app\cspman\model\controller\project_task\summary as rs_project_task;

class summary extends \app\controller_admin
{
    var $rs_project_task;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        $this->rs_project_task = new rs_project_task();
        parent::before();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->rs_project_task->set_value('project_id', isset($params[0]) ? $params[0] : '');
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 並べ替えアクション
    // --------------------------------------------------------------------------------
    public function action_sort($params)
    {
        $rs_project_task->sort($params);
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
            'rs_project_task' => $this->rs_project_task->fetch_all(),
            'values'          => $this->rs_project_task->get_values(),
        );
        echo $this->render('cspman/controller/project_task/summary.twig', $vars);
    }
}
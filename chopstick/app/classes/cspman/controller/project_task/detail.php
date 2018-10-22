<?php
namespace app\cspman\controller\project_task;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\cspman\model\controller\project_task\detail as dataset_project_task_detail;

class detail extends \app\controller_admin
{
    private $dataset_project_task_detail;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        $this->dataset_project_task_detail = new dataset_project_task_detail();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dataset_project_task_detail->set_value('project_task_id', isset($params[0]) ? $params[0] : '');
        if ($this->dataset_project_task_detail->read() == false)
        {
            response::redirect(url::create('/cspman/project/summary'));
        }
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 削除
    // --------------------------------------------------------------------------------
    public function action_update()
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/csroot/admin/auth/login'));
        }
        $this->dataset_stack->post();
        if ($this->dataset_stack->read() == false)
        {
            response::redirect(url::create('/cspman/project_task', $this->dataset_project_detail->get_value('project_id')));
        }
        $this->dataset_stack->delete();
        response::redirect(url::create('/cspman/project_task', $this->dataset_project_detail->get_value('project_id')));
    }
    // ********************************************************************************
    // **** 表示
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    function display()
    {
        $vars = array
        (
            'values'    => $this->dataset_project_task_detail->get_values(),
        );
        echo $this->render('cspman/controller/project_task/detail/confirm.twig', $vars);
    }
}
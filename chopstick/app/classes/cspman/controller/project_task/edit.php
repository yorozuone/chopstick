<?php
namespace app\cspman\controller\project_task;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\cspman\model\controller\project_task\edit as dataset_stack;

class edit extends \app\controller_admin
{
    private $dataset_stack;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        $this->dataset_project_task = new dataset_project_task();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dataset_stack->set_value('project_task_id', isset($params[0]) ? $params[0] : '');
        if ($this->dataset_stack->read() == false)
        {
            response::redirect(url::create('/cspman/project/summary'));
        }
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 更新
    // --------------------------------------------------------------------------------
    public function action_update($params)
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/csroot/admin/auth/login'));
        }
        $this->dataset_stack->post();
        if ($this->dataset_stack->check())
        {
            $this->dataset_stack->update();
            response::redirect(url::create('/cspman/project_task', array($this->dataset_stack->get_value('project_task_id'))));
        }
        else
        {
            $this->display();
        }
    }
    // ********************************************************************************
    // **** 表示
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    public function display()
    {
        $vars = array
        (
            'is_valid'          => $this->dataset_stack->is_valid,
            'values'            => $this->dataset_stack->get_values(),
            'error_messages'    => $this->dataset_stack->get_error_messages(),
        );
        echo $this->render('cspman/controller/project_task/edit/edit.twig', $vars);
    }
}
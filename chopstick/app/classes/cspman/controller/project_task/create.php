<?php
namespace app\cspman\controller\project_task;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\cspman\model\controller\project_task\create as dataset_project_task;

class create extends \app\controller_admin
{
    private $dataset_project_task;
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
        $this->dataset_project_task->set_value('project_id', isset($params[0]) ? $params[0] : '');
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 作成
    // --------------------------------------------------------------------------------
    public function action_update($params)
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/csroot/admin/auth/login'));
        }
        $this->dataset_project_task->post();
        //
        if ($this->dataset_project_task->check())
        {
            $this->dataset_project_task->create();
            response::redirect(url::create('/cspman/project_task/summary', array($this->dataset_project_task->get_value('project_task_id'))));
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
            'is_valid'          => $this->dataset_project_task->is_valid,
            'values'            => $this->dataset_project_task->get_values(),
            'error_messages'    => $this->dataset_project_task->get_error_messages(),
        );
        echo $this->render('cspman/controller/project_task/create/edit.twig', $vars);
    }
}
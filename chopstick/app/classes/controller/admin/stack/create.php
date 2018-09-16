<?php
namespace app\controller\admin\stack;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\model\controller\admin\stack\create as dset_stack;

class create extends \app\controller_admin
{
    private $dset_stack;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        $this->dset_stack = new dset_stack();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $stackgroup_id = isset($params[0]) ? $params[0] : '';
        $this->dset_stack->set_value('stackgroup_id', $stackgroup_id);
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
            response::redirect(url::create('/admin/auth/login'));
        }
        $this->dset_stack->post();
        //
        if ($this->dset_stack->check())
        {
            $this->dset_stack->create();
            response::redirect(url::create('/admin/stack/summary', array($this->dset_stack->get_value('stackgroup_id'))));
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
            'is_valid'                  => $this->dset_stack->is_valid,
            'rs_stackgroup'           => $this->dset_stack->fetch_stackgroup(),
            'dset_stack_values'         => $this->dset_stack->get_values(),
            'dset_stack_error_messages' => $this->dset_stack->get_error_messages(),
        );
        echo $this->render('controller/admin/stack/create/edit.twig', $vars);
    }
}
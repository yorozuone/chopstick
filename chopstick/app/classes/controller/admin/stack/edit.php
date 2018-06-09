<?php
namespace app\controller\admin\stack;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\model\controller\admin\stack\edit as dset_stack;

class edit extends \app\controller_auth
{
    private $dset_stack;
    //
    // ********************************************************************************
    // ****
    // **** アクション
    // ****
    // ********************************************************************************
    //
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    //
    public function before()
    {
        parent::before();
        //
        $this->dset_stack = new dset_stack();
    }
    //
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    //
    public function action_index($params)
    {
        $this->dset_stack->set_value('stack_key', isset($params[0]) ? $params[0] : '');
        if ($this->dset_stack->read() == false)
        {
            response::redirect(url::create('/admin/stack/summary'));
        }
        $this->display();
    }
    //
    // --------------------------------------------------------------------------------
    // 更新
    // --------------------------------------------------------------------------------
    //
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
            $this->dset_stack->update();
            response::redirect(url::create('/admin/stack/summary', array($this->dset_stack->get_value('stackgroup_id'))));
        }
        else
        {
            $this->display();
        }
    }
    //
    // ********************************************************************************
    // ****
    // **** 表示
    // ****
    // ********************************************************************************
    //
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    //
    public function display()
    {
        $vars = array
        (
            'is_valid'                  => $this->dset_stack->is_valid,
            'drec_stackgroup'           => $this->dset_stack->fetch_stackgroup(),
            'dset_stack_values'         => $this->dset_stack->get_values(),
            'dset_stack_error_messages' => $this->dset_stack->get_error_messages(),
        );
        echo $this->render('controller/admin/stack/edit/edit.twig', $vars);
    }
}
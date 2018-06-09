<?php
namespace app\controller\admin\stack;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\model\controller\admin\stack\delete as dset_stack;

class delete extends \app\controller_auth
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
            response::redirect(url::create('/admin/stackgroup'));
        }
        $this->display();
    }
    //
    // --------------------------------------------------------------------------------
    // 削除
    // --------------------------------------------------------------------------------
    //
    public function action_update()
    {
        if (!csrf::check())
        {
                auth::logout();
                response::redirect(url::create('/admin/auth/login'));
        }
        $this->dset_stack->post();
        //
        if ($this->dset_stack->read() == false)
        {
            response::redirect(url::create('/admin/stack/summary'));
        }
        $this->dset_stack->delete();
        response::redirect(url::create('/admin/stack/summary', array($this->dset_stack->get_value('stackgroup_id'))));
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
    function display()
    {
        $vars = array
        (
            'dset_stack_values'           => $this->dset_stack->get_values(),
        );
        echo $this->render('controller/admin/delete/confirm.twig', $vars);
    }
}
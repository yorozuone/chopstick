<?php
namespace app\controller\admin\stackgroup;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\model\controller\admin\stackgroup\delete as dset_stackgroup;

class delete extends \app\controller_auth
{
    private $dset_stackgroup;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        //
        $this->dset_stackgroup = new dset_stackgroup();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dset_stackgroup->set_value('stackgroup_id', isset($params[0]) ? $params[0] : '');
        if ($this->dset_stackgroup->read() == false)
        {
            response::redirect(url::create('/admin/stackgroup/summary'));
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
                response::redirect(url::create('/admin/auth/login'));
        }
        $this->dset_stackgroup->post();
        //
        if ($this->dset_stackgroup->read() == false)
        {
            response::redirect(url::create('/admin/stackgroup/summary'));
        }
        $this->dset_stackgroup->delete();
        response::redirect(url::create('/admin/stackgroup/summary'));
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
            'dset_stackgroup_values'          => $this->dset_stackgroup->get_values(),
            'dset_stackgroup_error_messages'  => $this->dset_stackgroup->get_error_messages(),
        );
        echo $this->render('controller/admin/stackgroup/delete/confirm.twig', $vars);
    }
}
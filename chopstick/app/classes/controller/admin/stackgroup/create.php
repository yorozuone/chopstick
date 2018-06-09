<?php
namespace app\controller\admin\stackgroup;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\model\controller\admin\stackgroup\create as dset_stackgroup;

class create extends \app\controller_auth
{
    private $dset_stackgroup = null;
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
        //
        $this->dset_stackgroup->post();
        //
        if ($this->dset_stackgroup->check())
        {
            $this->dset_stackgroup->create();
            response::redirect(url::create('/admin/stackgroup/summary'));
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
    private function display()
    {
        $vars = array
        (
            'is_valid'                          => $this->dset_stackgroup->is_valid,
            'dset_stackgroup_values'            => $this->dset_stackgroup->get_values(),
            'dset_stackgroup_error_messages'    => $this->dset_stackgroup->get_error_messages(),
        );
        echo $this->render('controller/admin/stackgroup/create/edit.twig', $vars);
    }
}
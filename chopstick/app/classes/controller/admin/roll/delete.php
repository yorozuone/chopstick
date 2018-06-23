<?php
namespace app\controller\admin\roll;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\model\controller\admin\roll\delete as dset_roll;

class delete extends \app\controller_admin
{
    private $dset_roll;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        $this->dset_roll = new dset_roll();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dset_group->set_value('roll_key', isset($params[0]) ? $params[0] : '');
        if ($this->dset_roll->read() == false)
        {
            response::redirect(url::create('/admin/roll'));
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
        $this->dset_roll->post();
        if ($this->dset_roll->read() == false)
        {
            response::redirect(url::create('/admin/roll/summary'));
        }
        $this->dset_roll->delete();
        response::redirect(url::create('/admin/roll/summary'));
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
            'dset_roll_values' => $this->dset_roll->get_values(),
        );
        echo $this->render('controller/admin/delete/confirm.twig', $vars);
    }
}
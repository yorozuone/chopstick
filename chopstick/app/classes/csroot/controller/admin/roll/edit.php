<?php
namespace app\csroot\controller\admin\roll;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\csroot\model\controller\admin\roll\edit as dset_roll;

class edit extends \app\controller_admin
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
        $this->dset_roll->set_value('roll_key', isset($params[0]) ? $params[0] : '');
        if ($this->dset_roll->read() == false)
        {
            response::redirect(url::create('/admin/roll/summary'));
        }
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 更新
    // --------------------------------------------------------------------------------
    public function action_update()
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/csroot/admin/auth/login'));
        }
        $this->dset_roll->post();
        if ($this->dset_roll->check())
        {
            $this->dset_roll->update();
            response::redirect(url::create('/admin/roll/summary'));
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
            'is_valid'                  => $this->dset_roll->is_valid,
            'dset_roll_values'         => $this->dset_roll->get_values(),
            'dset_roll_error_messages' => $this->dset_roll->get_error_messages(),
        );
        echo $this->render('cscms/controller/admin/roll/edit/edit.twig', $vars);
    }
}
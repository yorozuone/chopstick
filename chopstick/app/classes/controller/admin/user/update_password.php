<?php
namespace app\controller\admin\user;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;
use \core\user;

// dataset
use \app\model\controller\admin\user\update_password as dset_user;

class update_password extends \app\controller_auth
{
    private $dset_user = null;
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
        $this->dset_user = new dset_user();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dset_user->set_value('user_id', isset($params[0]) ? $params[0] : '');
        if ($this->dset_user->read() == false)
        {
            response::redirect(url::create('/admin/user/summary'));
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
            response::redirect(url::create('/admin/auth/login'));
        }
        $this->dset_user->post();
        //
        if ($this->dset_user->check())
        {
            $this->dset_user->update_password();
            response::redirect(url::create('/admin/user/summary'));
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
            'dset_user_values'            => $this->dset_user->get_values(),
            'dset_user_error_messages'    => $this->dset_user->get_error_messages(),
        );
        echo $this->render('controller/admin/user/update_password/edit.twig', $vars);
    }
}
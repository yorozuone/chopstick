<?php
namespace app\csroot\controller\admin\user;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;
use \core\user;

// dataset
use \app\csroot\model\controller\admin\user\delete as dset_user;

class delete extends \app\controller_admin
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
    // 削除
    // --------------------------------------------------------------------------------
    public function action_update()
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/csroot/admin/auth/login'));
        }
        $this->field->post();
        //
        if (user::read($this->field) == false)
        {
            response::redirect(url::create('/admin/user/summary'));
        }
        $dset_user->delete();
        response::redirect(url::create('/admin/user/summary'));
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
            'dset_user_values'            => $this->dset_user->get_values(),
            'dset_user_error_messages'    => $this->dset_user->get_error_messages(),
        );
        echo $this->render('cscms/controller/admin/user/delete/confirm.twig', $vars);
    }
}
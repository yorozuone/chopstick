<?php
namespace app\controller\admin\user;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;
use \core\user;

// dataset
use \app\model\controller\admin\user\create as dset_user;

class create extends \app\controller_admin
{
    private $dset_user = null;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // ------------------------------------------------------------
    // コントローラー起動事前処理
    // ------------------------------------------------------------
    public function before()
    {
        parent::before();
        //
        $this->dset_user = new dset_user();
    }
    // ------------------------------------------------------------
    // 既定
    // ------------------------------------------------------------
    public function action_index($params)
    {
        $this->display();
    }
    // ------------------------------------------------------------
    // 作成
    // ------------------------------------------------------------
    public function action_update($params)
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
            $this->dset_user->create();
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
    // ------------------------------------------------------------
    // 表示
    // ------------------------------------------------------------
    public function display()
    {
        $vars = array
        (
            'is_valid'                  => $this->dset_user->is_valid,
            'drec_group'                => $this->dset_user->fetch_group_all(),
            'dset_user_values'          => $this->dset_user->get_values(),
            'dset_user_error_messages'  => $this->dset_user->get_error_messages(),
        );
        array_unshift($vars['drec_group'], array('group_key'=>'', 'caption'=>'(所属グループを選択してください)'));
        echo $this->render('controller/admin/user/create/edit.twig', $vars);
    }
}
<?php
namespace app\controller\admin\auth;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;
use \app\model\controller\admin\auth\login as dataset_login;

class login extends \app\controller
{
    private $dataset_login;
    // ********************************************************************************
    // ****
    // **** アクション
    // ****
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        //
        $this->dataset_login = new dataset_login();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    function action_index()
    {
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // ログイン処理
    // --------------------------------------------------------------------------------
    function action_update()
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/admin/auth/login'));
        }
        $this->dataset_login->post();
        //
        $this->dataset_login->check();
        //
        if ($this->dataset_login->is_valid)
        {
            auth::login
            (
                $this->dataset_login->get_value('username'),
                $this->dataset_login->get_value('password')
            );
            response::redirect(url::create('/admin/menu'));
        }
        $this->display();
    }
    // ********************************************************************************
    // ****
    // **** 表示
    // ****
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    private function display()
    {
        $vars = array
        (
            'values' => $this->dataset_login->get_values(),
            'error_messages' => $this->dataset_login->get_error_messages(),
        );
        echo $this->render('controller/admin/auth/login/edit.twig', $vars);
    }
}

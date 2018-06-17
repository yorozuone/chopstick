<?php
namespace app\controller\admin\composer;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

use \app\model\controller\admin\composer\create as dset_composer;

class create extends \app\controller_admin
{
    private $dset_composer = null;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        $this->dset_composer = new dset_composer();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index()
    {
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 作成
    // --------------------------------------------------------------------------------
    public function action_update()
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/admin/auth/login'));
        }
        // 受信
        $this->dset_composer->post();
        // チェック後処理
        if ($this->dset_composer->check())
        {
            $this->dset_composer->create();
            response::redirect(url::create('/admin/composer/summary'));
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
            'is_valid'                      => $this->dset_composer->is_valid,
            'dset_composer_values'          => $this->dset_composer->get_values(),
            'dset_composer_error_messages'  => $this->dset_composer->get_error_messages(),
        );
        echo $this->render('controller/admin/composer/create/edit.twig', $vars);
    }
}
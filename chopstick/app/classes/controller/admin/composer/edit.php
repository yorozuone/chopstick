<?php
namespace app\controller\admin\composer;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

use \app\model\controller\admin\composer\edit as dset_composer;

class edit extends \app\controller_admin
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
    public function action_index($params)
    {
        $composer_key = isset($this->route->params[0]) ? $this->route->params[0] : '';
        $this->dset_composer->set_value('composer_key', $composer_key);
        if ($this->dset_composer->read() == false)
        {
            response::redirect(url::create('/admin/composer/summary'));
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
        // 受信
        $this->dset_composer->post();
        // 存在確認
        $composer_key = $this->dset_composer->get_value('composer_key');
        // 入力チェック後、処理実行
        if ($this->dset_composer->check())
        {
            $this->dset_composer->update();
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
            'dset_composer_values'          => $this->dset_composer->get_values(),
            'dset_composer_error_messages'  => $this->dset_composer->get_error_messages(),
        );
        echo $this->render('controller/admin/composer/edit/edit.twig', $vars);
    }
}
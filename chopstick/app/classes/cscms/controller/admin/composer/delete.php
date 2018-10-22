<?php
namespace app\cscms\controller\admin\composer;

use \core\auth;
use \core\csrf;
use \core\fieldset;
use \core\response;
use \core\url;

use \app\cscms\model\controller\admin\composer\delete as dataset_composer;

class delete extends \app\controller_admin
{
    private $dataset_composer = null;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        $this->dataset_composer = new dataset_composer();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index()
    {
        $composer_key = isset($this->route->params[0]) ? $this->route->params[0] : '';
        $this->dataset_composer->set_value('composer_key', $composer_key);
        //
        if ($this->dataset_composer->read() == false)
        {
            response::redirect(url::create('/cscms/admin/composer/summary'));
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
        // 受信
        $this->dataset_composer->post();
        // 存在確認
        $composer_key = $this->dataset_composer->get_value('composer_key');
        if ($this->dataset_composer->read() == false)
        {
            response::redirect(url::create('/cscms/admin/composer/summary'));
        }
        // 入力チェック後、処理実行
        if ($this->dataset_composer->check())
        {
            $this->dataset_composer->delete();
        }
        response::redirect(url::create('/cscms/admin/composer/summary'));
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
            'values'          => $this->dataset_composer->get_values(),
            'error_messages'  => $this->dataset_composer->get_error_messages(),
        );
        echo $this->render('cscms/controller/admin/composer/delete/confirm.twig', $vars);
    }
}
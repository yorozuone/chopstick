<?php
namespace app\controller\admin\composer;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

use \app\model\controller\admin\composer\edit as dataset_composer;
use \app\model\recordset\composer_block as recordataset_composer_block;

class edit extends \app\controller_admin
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
    public function action_index($params)
    {
        $composer_key = isset($this->route->params[0]) ? $this->route->params[0] : '';
        $this->dataset_composer->set_value('composer_key', $composer_key);
        if ($this->dataset_composer->read() == false)
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
        $this->dataset_composer->post();
        // 存在確認
        $composer_key = $this->dataset_composer->get_value('composer_key');
        // 入力チェック後、処理実行
        if ($this->dataset_composer->check())
        {
            $this->dataset_composer->update();
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
            'values'                        => $this->dataset_composer->get_values(),
            'error_messages'                => $this->dataset_composer->get_error_messages(),
            'recordset_template'            => \app\model\recordset\template::fetch_all(),
        );
        echo $this->render('controller/admin/composer/edit/edit.twig', $vars);
    }
}
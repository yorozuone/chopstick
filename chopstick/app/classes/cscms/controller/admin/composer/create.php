<?php
namespace app\cscms\controller\admin\composer;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

use \app\cscms\model\controller\admin\composer\create as dataset_composer;

class create extends \app\controller_admin
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
            response::redirect(url::create('/csroot/admin/auth/login'));
        }
        // 受信
        $this->dataset_composer->post();
        // チェック後処理
        if ($this->dataset_composer->check())
        {
            $this->dataset_composer->create();
            response::redirect(url::create('/cscms/admin/composer/summary'));
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
            'is_valid'              => $this->dataset_composer->is_valid,
            'values'                => $this->dataset_composer->get_values(),
            'error_messages'        => $this->dataset_composer->get_error_messages(),
            'recordset_template'    => \app\cscms\model\recordset\template::fetch_all(),
        );
        echo $this->render('cscms/controller/admin/composer/create/edit.twig', $vars);
    }
}
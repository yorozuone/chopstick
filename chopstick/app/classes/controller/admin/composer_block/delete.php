<?php
namespace app\controller\admin\composer_block;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

use \app\model\controller\admin\composer_block\delete as dataset_composer_block;

class delete extends \app\controller_admin
{
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        $this->dataset_composer_block = new dataset_composer_block();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dataset_composer_block->set_value('composer_key', isset($params[0]) ? $params[0] : '');
        $this->dataset_composer_block->set_value('composer_block_key', isset($params[1]) ? $params[1] : '');
        if ($this->dataset_composer_block->read() == false)
        {
            response::redirect(url::create('/admin/composer_block/summary', array($this->dataset_composer_block->get_value('composer_key'))));
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
            response::redirect(url::create('/admin/auth/login'));
        }
        $this->dataset_composer_block->post();
        if ($this->dataset_composer_block->read() == false)
        {
            response::redirect(url::create('/admin/composer_block/summary', array($this->dataset_composer_block->get_value('composer_key'))));
        }
        $this->dataset_composer_block->delete();
        response::redirect(url::create('/admin/composer_block/summary', array($this->dataset_composer_block->get_value('composer_key'))));
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
            'values'            => $this->dataset_composer_block->get_values(),
            'error_messages'    => $this->dataset_composer_block->get_error_messages(),
        );
        echo $this->render('controller/admin/composer_block/delete/confirm.twig', $vars);
    }
}
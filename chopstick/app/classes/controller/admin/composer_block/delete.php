<?php
namespace app\controller\admin\composer_block;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

use \app\model\controller\admin\composer_block\delete as dset_composer_block;

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
        $this->dset_composer_block = new dset_composer_block();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dset_composer_block->set_value('composer_key', isset($params[0]) ? $params[0] : '');
        $this->dset_composer_block->set_value('composer_block_key', isset($params[1]) ? $params[1] : '');
        if ($this->dset_composer_block->read() == false)
        {
            response::redirect(url::create('/admin/composer_block/summary', array($this->dset_composer_block->get_value('composer_key'))));
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
        $this->dset_composer_block->post();
        if ($this->dset_composer_block->read() == false)
        {
            response::redirect(url::create('/admin/composer_block/summary', array($this->dset_composer_block->get_value('composer_key'))));
        }
        $this->dset_composer_block->delete();
        response::redirect(url::create('/admin/composer_block/summary', array($this->dset_composer_block->get_value('composer_key'))));
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
            'dset_composer_block_values'            => $this->dset_composer_block->get_values(),
            'dset_composer_block_error_messages'    => $this->dset_composer_block->get_error_messages(),
        );
        echo $this->render('controller/admin/composer_block/delete/confirm.twig', $vars);
    }
}
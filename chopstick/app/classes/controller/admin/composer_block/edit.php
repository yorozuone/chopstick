<?php
namespace app\controller\admin\composer_block;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

use \app\model\controller\admin\composer_block\edit as dset_composer_block;

class edit extends \app\controller_auth
{
    private $dset_composer_block = null;
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
        $this->dset_composer_block = new dset_composer_block();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dset_composer_block->set_value('composer_key',       isset($params[0]) ? $params[0] : '');
        $this->dset_composer_block->set_value('composer_block_key', isset($params[1]) ? $params[1] : '');
        //
        if ($this->dset_composer_block->read() == false)
        {
            response::redirect(url::create('/admin/composer_block/summary'));
        }
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 更新
    // --------------------------------------------------------------------------------
    public function action_update($params)
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/admin/auth/login'));
        }
        $this->dset_composer_block->post();
        //
        if ($this->dset_composer_block->check())
        {
            $this->dset_composer_block->update();
            response::redirect(url::create('/admin/composer_block/summary', array($this->dset_composer_block->get_value('composer_key'))));
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
    public function display()
    {
        $vars = array
        (
            'is_valid'                              => $this->dset_composer_block->is_valid,
            'dset_composer_block_values'            => $this->dset_composer_block->get_values(),
            'dset_composer_block_error_messages'    => $this->dset_composer_block->get_error_messages(),
        );
        echo $this->render('controller/admin/composer_block/edit/edit.twig', $vars);
    }
}
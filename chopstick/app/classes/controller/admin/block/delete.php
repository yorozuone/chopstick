<?php
namespace app\controller\admin\block;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

use \app\model\controller\admin\block\delete as dset_block;

class delete extends \app\controller_admin
{
    private $dset_block = null;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    //
    public function before()
    {
        parent::before();
        //
        $this->dset_block = new dset_block();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dset_block->set_value('block_key', isset($params[0]) ? $params[0] : '');
        if ($this->dset_block->read() == false)
        {
            response::redirect(url::create('/admin/block/summary'));
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
        $this->dset_block->post();
        //
        if ($this->dset_block->read() == false)
        {
            response::redirect(url::create('/admin/block/summary'));
        }
        $this->dset_block->delete();
        response::redirect(url::create('/admin/block/summary'));
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
            'dset_block_values'         => $this->dset_block->get_values(),
            'dset_block_error_messages' => $this->dset_block->get_error_messages(),
        );
        echo $this->render('controller/admin/block/delete/confirm.twig', $vars);
    }
}
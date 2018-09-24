<?php
namespace app\controller\admin\block;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

use \app\model\controller\admin\block\delete as dataset_block;

class delete extends \app\controller_admin
{
    private $dataset_block = null;
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
        $this->dataset_block = new dataset_block();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dataset_block->set_value('block_key', isset($params[0]) ? $params[0] : '');
        if ($this->dataset_block->read() == false)
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
        $this->dataset_block->post();
        //
        if ($this->dataset_block->read() == false)
        {
            response::redirect(url::create('/admin/block/summary'));
        }
        $this->dataset_block->delete();
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
            'values'          => $this->dataset_block->get_values(),
            'error_messages'  => $this->dataset_block->get_error_messages(),
        );
        echo $this->render('controller/admin/block/delete/confirm.twig', $vars);
    }
}
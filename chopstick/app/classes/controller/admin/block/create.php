<?php
namespace app\controller\admin\block;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

use \app\model\controller\admin\block\create as dataset_block;

class create extends \app\controller_admin
{
    private $dataset_block = null;
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
        $this->dataset_block = new dataset_block();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dataset_block->set_value('block_key', isset($params[0]) ? $params[0] : '');
        if ($this->dataset_block->read_from_class() == false)
        {
            response::redirect(url::create('/admin/block/summary'));
        }
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 作成
    // --------------------------------------------------------------------------------
    public function action_update($params)
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/admin/auth/login'));
        }
        $this->dataset_block->post();
        //
        if ($this->dataset_block->check())
        {
            $this->dataset_block->create();
            response::redirect(url::create('/admin/block/summary'));
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
            'is_valid'          => $this->dataset_block->is_valid,
            'values'            => $this->dataset_block->get_values(),
            'error_messages'    => $this->dataset_block->get_error_messages(),
        );
        echo $this->render('controller/admin/block/create/edit.twig', $vars);
    }
}
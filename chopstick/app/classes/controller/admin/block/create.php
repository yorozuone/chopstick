<?php
namespace app\controller\admin\block;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

use \app\model\controller\admin\block\create as dset_block;

class create extends \app\controller_auth
{
    private $dset_block = null;
    //
    // ********************************************************************************
    // ****
    // **** アクション
    // ****
    // ********************************************************************************
    //
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
    //
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    //
    public function action_index($params)
    {
        $this->dset_block->set_value('block_key', isset($params[0]) ? $params[0] : '');
        if ($this->dset_block->read_from_class() == false)
        {
            response::redirect(url::create('/admin/block/summary'));
        }
        $this->display();
    }
    //
    // --------------------------------------------------------------------------------
    // 作成
    // --------------------------------------------------------------------------------
    //
    public function action_update($params)
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/admin/auth/login'));
        }
        $this->dset_block->post();
        //
        if ($this->dset_block->check())
        {
            $this->dset_block->create();
            response::redirect(url::create('/admin/block/summary'));
        } 
        else
        {
            $this->display();
        }
    }
    //
    // ********************************************************************************
    // ****
    // **** 表示
    // ****
    // ********************************************************************************
    //
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    //
    public function display()
    {
        $vars = array
        (
            'is_valid'                  => $this->dset_block->is_valid,
            'dset_block_values'         => $this->dset_block->get_values(),
            'dset_block_error_messages' => $this->dset_block->get_error_messages(),
        );
        echo $this->render('controller/admin/block/create/edit.twig', $vars);
    }
}
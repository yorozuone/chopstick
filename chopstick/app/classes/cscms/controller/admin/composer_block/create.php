<?php
namespace app\cscms\controller\admin\composer_block;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

use \app\cscms\model\recordset\block as recordset_block;
use \app\cscms\model\controller\admin\composer_block\create as dataset_composer_block;

class create extends \app\controller_admin
{
    private $dataset_composer_block = null;
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
            response::redirect(url::create('/csroot/admin/auth/login'));
        }
        $this->dataset_composer_block->post();
        if ($this->dataset_composer_block->check())
        {
            $this->dataset_composer_block->create();
            response::redirect(url::create('/cscms/admin/composer_block/summary', array($this->dataset_composer_block->get_value('composer_key'))));
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
            'rs_block'        => recordset_block::fetch_all(),
            'is_valid'          => $this->dataset_composer_block->is_valid,
            'values'            => $this->dataset_composer_block->get_values(),
            'error_messages'    => $this->dataset_composer_block->get_error_messages(),
        );
        array_unshift
        (
            $vars['rs_block'],
            array
            (
                'block_key'=>'',
                'name'=>'（ブロックを選択してください）'
            )
        );
        echo $this->render('cscms/controller/admin/composer_block/create/edit.twig', $vars);
    }
}
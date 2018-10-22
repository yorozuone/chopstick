<?php
namespace app\cscms\controller\admin\composer_block;

use \core\response;
use \core\url;

use \app\cscms\model\controller\admin\composer_block\summary as rs_composer_block;

class summary extends \app\controller_admin
{
    private $composer_key;
    private $rs_composer_block;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index()
    {
        $this->composer_key = isset($this->route->params[0]) ? $this->route->params[0] :-10;
        $this->rs_composer_block = new rs_composer_block();
        $this->rs_composer_block->set_value('composer_key', $this->composer_key);
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 並べ替えアクション
    // --------------------------------------------------------------------------------
    public function action_sort($params)
    {
        $this->dset_block->sort($params);
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
            'composer_key'          => $this->composer_key,
            'rs_composer_block'   => $this->rs_composer_block->fetch_all(),
        );
        echo $this->render('cscms/controller/admin/composer_block/summary.twig', $vars);
    }
}
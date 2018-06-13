<?php
namespace app\controller\admin\composer_block;

use \core\response;
use \core\url;

use \app\model\controller\admin\composer_block\summary as drec_composer_block;

class summary extends \app\controller_auth
{
    private $composer_key;
    private $drec_composer_block;
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
        $composer_key = isset($this->route->params[0]) ? $this->route->params[0] :-10;
        $this->drec_composer_block = new drec_composer_block();
        $this->drec_composer_block->set_value('composer_key', $composer_key);
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
            'drec_composer_block'   => $this->drec_composer_block->fetch_all(),
        );
        echo $this->render('controller/admin/composer_block/summary.twig', $vars);
    }
}
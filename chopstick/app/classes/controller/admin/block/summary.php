<?php
namespace app\controller\admin\block;

use \core\response;
use \core\url;

use \app\model\controller\admin\block\summary as rs_block;

class summary extends \app\controller_admin
{
    private $rs_block;
    //
    // ********************************************************************************
    // ****
    // **** アクション
    // ****
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        //
        $this->rs_block = new rs_block();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index()
    {
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // インストール
    // --------------------------------------------------------------------------------
    public function action_install($params)
    {
        $this->rs_block->install($params[0]);
        response::redirect(url::create('/admin/block/summary'));
    }
    // --------------------------------------------------------------------------------
    // 削除
    // --------------------------------------------------------------------------------
    public function action_remove()
    {
        $this->rs_block->install($remove[0]);
        response::redirect(url::create('/admin/block/summary'));
    }
    // ********************************************************************************
    // **** 表示
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    private function display()
    {
        $rs_block = $this->rs_block->fetch_all();
        //
        $rs_block_1 = array();
        $rs_block_2 = array();
        // 未インストールとインストール済みを分離
        foreach($rs_block as $v)
        {
            if ($v['installed'] === false)
            {
                $rs_block_1[] = $v;
            }
            else
            {
                $rs_block_2[] = $v;
            }
        }
        //
        $vars = array
        (
            'rs_block_1' => $rs_block_1,
            'rs_block_2' => $rs_block_2,
        );
        echo $this->render('controller/admin/block/summary.twig', $vars);
    }
}
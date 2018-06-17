<?php
namespace app\controller\admin\block;

use \core\response;
use \core\url;

use \app\model\controller\admin\block\summary as drec_block;

class summary extends \app\controller_admin
{
    private $drec_block;
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
        $this->drec_block = new drec_block();
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
        $this->drec_block->install($params[0]);
        response::redirect(url::create('/admin/block/summary'));
    }
    // --------------------------------------------------------------------------------
    // 削除
    // --------------------------------------------------------------------------------
    public function action_remove()
    {
        $this->drec_block->install($remove[0]);
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
        $drec_block = $this->drec_block->fetch_all();
        //
        $drec_block_1 = array();
        $drec_block_2 = array();
        // 未インストールとインストール済みを分離
        foreach($drec_block as $v)
        {
            if ($v['installed'] === false)
            {
                $drec_block_1[] = $v;
            }
            else
            {
                $drec_block_2[] = $v;
            }
        }
        //
        $vars = array
        (
            'drec_block_1' => $drec_block_1,
            'drec_block_2' => $drec_block_2,
        );
        echo $this->render('controller/admin/block/summary.twig', $vars);
    }
}
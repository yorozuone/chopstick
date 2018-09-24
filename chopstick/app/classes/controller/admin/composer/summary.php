<?php
namespace app\controller\admin\composer;

use \core\response;
use \core\url;

use \app\model\controller\admin\composer\summary as recordset_composer;

class summary extends \app\controller_admin
{
    private $recordset_composer;
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
        $this->recordset_composer = new recordset_composer();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 並べ替えアクション
    // --------------------------------------------------------------------------------
    public function action_sort($params)
    {
        $this->recordset_composer->sort($params);
    }
    // ********************************************************************************
    // **** 表示
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    private function display()
    {
        $recordset_composer = new recordset_composer();
        //
        $vars = array
        (
            'recordset_composer' => $this->recordset_composer->fetch_all(),
        );
        echo $this->render('controller/admin/composer/summary.twig', $vars);
    }
}
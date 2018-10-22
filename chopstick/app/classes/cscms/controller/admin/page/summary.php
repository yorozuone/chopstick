<?php
namespace app\cscms\controller\admin\page;

use \core\response;
use \core\url;

use \app\cscms\model\controller\admin\page\summary as recordset_page;

class summary extends \app\controller_admin
{
    private $parent_page_id;
    private $recordset_page;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        $this->recordset_page = new recordset_page();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->parent_page_id = isset($params[0]) ? $params[0] : 0;
        $this->display();
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
            'parent_page_id' => $this->parent_page_id,
            'recordset_page' => $this->recordset_page->fetch_all(),
        );
        echo $this->render('cscms/controller/admin/page/summary.twig', $vars);
    }
}
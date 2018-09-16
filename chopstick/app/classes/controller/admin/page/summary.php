<?php
namespace app\controller\admin\page;

use \core\response;
use \core\url;

use \app\model\controller\admin\page\summary as rs_page;

class summary extends \app\controller_admin
{
    private $rs_page;
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
        $this->rs_page = new rs_page();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->rs_page->set_value('parent_page_id', isset($params[0]) ? $params[0] : 0);
        //
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 並べ替えアクション
    // --------------------------------------------------------------------------------
    public function action_sort($params)
    {
        $this->rs_page->sort($params);
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
            'rs_page' => $this->rs_page->fetch_all(),
            'rs_page_values' => $this->rs_page->get_values(),
            'dr_beradcrumb' => \app\model\helper\page::fetch_beradcrumb($this->rs_page->get_value('parent_page_id')),
        );
        echo $this->render('controller/admin/page/summary.twig', $vars);
    }
}
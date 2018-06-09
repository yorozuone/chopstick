<?php
namespace app\controller\admin\page;

use \core\response;
use \core\url;

use \app\model\controller\admin\page\summary as drec_page;

class summary extends \app\controller_auth
{
    private $drec_page;
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
        $this->drec_page = new drec_page();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->drec_page->set_value('parent_page_id', isset($params[0]) ? $params[0] : 0);
        //
        $this->display();
    }
    //
    // --------------------------------------------------------------------------------
    // 並べ替えアクション
    // --------------------------------------------------------------------------------
    //
    public function action_sort($params)
    {
        $this->drec_page->sort($params);
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
    private function display()
    {
        $vars = array
        (
            'drec_page' => $this->drec_page->fetch_all(),
            'drec_page_values' => $this->drec_page->get_values(),
            'dr_beradcrumb' => \app\model\helper\page::fetch_beradcrumb($this->drec_page->get_value('parent_page_id')),
        );
        echo $this->render('controller/admin/page/summary.twig', $vars);
    }
}
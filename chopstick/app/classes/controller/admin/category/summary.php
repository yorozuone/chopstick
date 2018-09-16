<?php
namespace app\controller\admin\category;

use \core\response;
use \core\url;

use \app\model\controller\admin\category\summary as rs_category;

class summary extends \app\controller_admin
{
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
        $parent_category_id = isset($this->route->params[0]) ? $this->route->params[0] : 0;
        $this->rs_category = new rs_category();
        $this->rs_category->set_value('parent_category_id', $parent_category_id);
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 並べ替えアクション
    // --------------------------------------------------------------------------------
    public function action_sort()
    {
        $this->dset_category->sort($this->route->params);
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
            'parent_category_id'    => $this->rs_category->get_value('parent_category_id'),
            'rs_category'         => $this->rs_category->fetch_all(),
            'rs_breadcrumb'       => $this->rs_category->fetch_beradcrumb(),
        );
        echo $this->render('controller/admin/category/summary.twig', $vars);
    }
}
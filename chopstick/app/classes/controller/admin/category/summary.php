<?php
namespace app\controller\admin\category;

use \core\response;
use \core\url;

use \app\model\controller\admin\category\summary as drec_category;

class summary extends \app\controller_auth
{
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
    }
    //
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    //
    public function action_index()
    {
        $parent_category_id = isset($this->params[0]) ? $this->params[0] : 0;
        //
        $this->drec_category = new drec_category();
        $this->drec_category->set_value('parent_category_id', $parent_category_id);
        //
        $this->display();
    }
    //
    // --------------------------------------------------------------------------------
    // 並べ替えアクション
    // --------------------------------------------------------------------------------
    //
    public function action_sort()
    {
        $this->dset_category->sort($this->params);
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
    //
    private function display()
    {
        $vars = array
        (
            'parent_category_id'    => $this->drec_category->get_value('parent_category_id'),
            'drec_category'         => $this->drec_category->fetch_all(),
            'drec_breadcrumb'       => $this->drec_category->fetch_beradcrumb(),
        );
        echo $this->render('controller/admin/category/summary.twig', $vars);
    }
}
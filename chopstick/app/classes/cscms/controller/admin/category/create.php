<?php
namespace app\cscms\controller\admin\category;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;
use \core\validation;

use \app\cscms\model\controller\admin\category\create as dataset_category;

class create extends \app\controller_admin
{
    private $dataset_category = null;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        $parent_category_id = isset($this->route->params[0]) ? $this->route->params[0] : 0;
        $this->dataset_category = new dataset_category();
        $this->dataset_category->set_value('parent_category_id', $parent_category_id);
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index()
    {
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 更新処理
    // --------------------------------------------------------------------------------
    public function action_update()
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/csroot/admin/auth/login'));
        }
        $this->dataset_category->post();
        //
        if ($this->dataset_category->check())
        {
            $this->dataset_category->create();
            response::redirect(url::create('/cscms/admin/category/summary', array($this->dataset_category->get_value('parent_category_id'))));
        }
        else
        {
            $this->display();
        }
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
            'values'            => $this->dataset_category->get_values(),
            'error_messages'    => $this->dataset_category->get_error_messages(),
            'rs_category_tree'  => \app\cscms\model\recordset\category::fetch_tree(),
        );
        array_unshift
        (
            $vars['rs_category_tree'],
            array
            (
                'category_id'=>0,
                'caption'=>'(root)',
                'tree_caption'=>'(root)'
            )
        );
        echo $this->render('cscms/controller/admin/category/create/edit.twig', $vars);
    }
}
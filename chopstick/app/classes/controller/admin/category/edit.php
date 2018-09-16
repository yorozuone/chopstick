<?php
namespace app\controller\admin\category;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;
use \core\validation;

use \app\model\controller\admin\category\edit as dset_category;

class edit extends \app\controller_admin
{
    private $dset_category = null;
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
        $this->dset_category = new dset_category();
        $this->dset_category->set_value('category_id', $parent_category_id);
        if ($this->dset_category->read() == false)
        {
            response::redirect(url::create('/admin/category/summary'));
        }
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index()
    {
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 更新
    // --------------------------------------------------------------------------------
    public function action_update()
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/admin/auth/login'));
        }
        $this->dset_category->post();
        //
        if ($this->dset_category->check())
        {
            $this->dset_category->update();
            response::redirect(url::create('/admin/category/summary', array($this->dset_category->get_value('parent_category_id'))));
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
            'dset_category_values'            => $this->dset_category->get_values(),
            'dset_category_error_messages'    => $this->dset_category->get_error_messages(),
            'rs_category_tree'              => \app\model\recordset\category::fetch_tree(),
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
        //
        echo $this->render('controller/admin/category/edit/edit.twig', $vars);
    }
}
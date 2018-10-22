<?php
namespace app\cscms\controller\admin\category;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;
use \core\validation;

use \app\cscms\model\controller\admin\category\delete as dataset_category;

class delete extends \app\controller_admin
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
        $this->dataset_category->set_value('category_id', $parent_category_id);
        if ($this->dataset_category->read() == false)
        {
            response::redirect(url::create('/cscms/admin/category/summary'));
        }
        if ($this->dataset_category->exists_child_category())
        {
            $this->display('cant');
            die();
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
    // 削除
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
            $this->dataset_category->delete();
        }
        response::redirect(url::create('/cscms/admin/category/summary', array($this->dataset_category->get_value('parent_category_id'))));
    }
    // ********************************************************************************
    // ****
    // **** 表示
    // ****
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    private function display($template_name = 'confirm')
    {
        $vars = array
        (
            'values'            => $this->dataset_category->get_values(),
            'error_messages'    => $this->dataset_category->get_error_messages(),
        );
        echo $this->render('cscms/controller/admin/category/delete/'.$template_name.'.twig', $vars);
    }
}
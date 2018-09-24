<?php
namespace app\controller\admin\page;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\model\controller\admin\page\create as dataset_page;

class create extends \app\controller_admin
{
    private $dataset_page;
    private $blocks = array();
    // ********************************************************************************
    // **** 既定の処理
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        $this->dataset_page = new dataset_page();
    }
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dataset_page->set_value('parent_page_id', isset($params[0]) ? $params[0] : 0);
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_entry($params)
    {
        $this->dataset_page->set_value('parent_page_id', isset($params[0]) ? $params[0] : 0);
        $this->dataset_page->set_value('composer_key',   isset($params[1]) ? $params[1] : 'default');
        if ($this->dataset_page->check() == false)
        {
            response::redirect(url::create('/admin/page/create', array($this->dataset_page->get_value('parent_page_id'))));
        }
        $this->dataset_page->create();       
        response::redirect(url::create('/admin/page/edit', array($this->dataset_page->get_value('page_id'))));
    }
    // ********************************************************************************
    // **** 表示
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    public function display()
    {
        $vars = array
        (
            'rs_composer' => \app\model\recordset\composer::fetch_all(),
            'values' => $this->dataset_page->get_values(),
        );
        echo $this->render('controller/admin/page/create/composer.twig', $vars);
    }
}
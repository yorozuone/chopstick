<?php
namespace app\controller\admin\page;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;
use \core\validation;

// dataset
use \app\model\controller\admin\page\delete as dataset_page;

class delete extends \app\controller_admin
{
    private $dataset_page;
    //
    private $composer_blocks;
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
        $this->dataset_page->set_value('page_id', isset($params[0]) ? $params[0] : 0);
        $this->dataset_page->read();
        if ($this->dataset_page->read() == false)
        {
            response::redirect(url::create('/admin/page/summary'));
        }
        $this->composer_blocks = $this->dataset_page->fetch_composer_block();
        foreach($this->composer_blocks  as $v)
        {
            $v->read();
        }
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 作成
    // --------------------------------------------------------------------------------
    public function action_update($params)
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/admin/auth/login'));
        }
        //
        $this->dataset_page->post();
        $this->composer_blocks = $this->dataset_page->fetch_composer_block();
        foreach($this->composer_blocks as $v)
        {
            $v->block_post();
        }
        //
        $this->dataset_page->delete();
        foreach($this->composer_blocks as $v)
        {
            $v->set_value('page_id', $this->dataset_page->get_value('page_id'));
            $v->delete();
        }
        response::redirect(url::create('/admin/page/summary', array($this->dataset_page->get_value('parent_page_id'))));
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
            'values'            => $this->dataset_page->get_values(),
            'error_messages'    => $this->dataset_page->get_error_messages(),
        );
        if (isset($this->composer_blocks))
        {
            $vars['composer_blocks'] = $this->composer_blocks;
        }
        echo $this->render('controller/admin/page/delete/confirm.twig', $vars);
    }
}
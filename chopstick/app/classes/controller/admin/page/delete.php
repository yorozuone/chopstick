<?php
namespace app\controller\admin\page;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;
use \core\validation;

// dataset
use \app\model\controller\admin\page\delete as dset_page;

class delete extends \app\controller_auth
{
    private $dset_page;
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
        //
        $this->dset_page = new dset_page();
    }
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dset_page->set_value('page_id', isset($params[0]) ? $params[0] : 0);
        $this->dset_page->read();
        if ($this->dset_page->read() == false)
        {
            response::redirect(url::create('/admin/page/summary'));
        }
        //
        $this->composer_blocks = $this->dset_page->fetch_composer_block();
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
        $this->dset_page->post();
        $this->composer_blocks = $this->dset_page->fetch_composer_block();
        foreach($this->composer_blocks as $v)
        {
            $v->block_post();
        }
        //
        $this->dset_page->delete();
        foreach($this->composer_blocks as $v)
        {
            $v->set_value('page_id', $this->dset_page->get_value('page_id'));
            $v->delete();
        }
        response::redirect(url::create('/admin/page/summary', array($this->dset_page->get_value('parent_page_id'))));
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
            'dset_page_values'          => $this->dset_page->get_values(),
            'dset_page_error_messages'  => $this->dset_page->get_error_messages(),
        );
        if (isset($this->composer_blocks))
        {
            $vars['composer_blocks'] = $this->composer_blocks;
        }
        echo $this->render('controller/admin/page/delete/confirm.twig', $vars);
    }
}
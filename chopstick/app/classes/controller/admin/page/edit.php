<?php
namespace app\controller\admin\page;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\model\controller\admin\page\edit as dset_page;

class edit extends \app\controller_auth
{
    private $dset_page;
    private $cblocks = array();
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
        //
        $this->dset_page->read();
        //
        if ($this->dset_page->read() == false)
        {
            response::redirect(url::create('/admin/page/summary'));
        }
        // ------------------------------------------------------------
        // コンポーザー・ブロック情報取得
        // ------------------------------------------------------------
        $this->composer_blocks = $this->dset_page->fetch_composer_block();
        //
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
        $is_valid = $this->dset_page->check();
        foreach($this->composer_blocks as $v)
        {
            if ($v->check() == false)
            {
                $is_valid = false;
            }
        }
        //
        if ($is_valid)
        {
            $this->dset_page->update();
            //
            foreach($this->composer_blocks as $v)
            {
                $v->update();
            }
            response::redirect(url::create('/admin/page/summary', array($this->dset_page->get_value('parent_page_id'))));
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
    public function display()
    {
        $vars = array
        (
            'is_valid'                  => $this->dset_page->is_valid,
            //
            'dset_page_values'          => $this->dset_page->get_values(),
            'dset_page_error_messages'  => $this->dset_page->get_error_messages(),
            //
            'drec_page_tree'            => \app\model\datasource\page::fetch_tree(),
            'drec_category_tree'        => \app\model\datasource\category::fetch_tree(),
            'drec_template'             => \app\model\datasource\template::fetch_all(),
        );
        if (isset($this->composer_blocks))
        {
            $vars['composer_blocks'] = $this->composer_blocks;
        }
        array_unshift
        (
            $vars['drec_page_tree'],
            array
            (
                'page_id'=>0,
                'page_title'=>'(root)',
                'tree_title'=>'(root)'
            )
        );
        echo $this->render('controller/admin/page/edit/edit.twig', $vars);
    }
}
<?php
namespace app\controller\admin\page;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\model\controller\admin\page\edit as dataset_page;

class edit extends \app\controller_admin
{
    private $dataset_page;
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
        // ------------------------------------------------------------
        // コンポーザー・ブロック情報取得
        // ------------------------------------------------------------
        $this->composer_blocks = $this->dataset_page->fetch_composer_block();
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
        $this->dataset_page->post();
        $this->composer_blocks = $this->dataset_page->fetch_composer_block();
        foreach($this->composer_blocks as $v)
        {
            $v->block_post();
        }
        //
        $is_valid = $this->dataset_page->check();
        foreach($this->composer_blocks as $v)
        {
            if ($v->block_check() == false)
            {
                $is_valid = false;
            }
        }
        //
        if ($is_valid)
        {
            $this->dataset_page->update();
            //
            foreach($this->composer_blocks as $v)
            {
                $v->update();
            }
            response::redirect(url::create('/admin/page/summary', array($this->dataset_page->get_value('parent_page_id'))));
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
            'is_valid'              => $this->dataset_page->is_valid,
            'values'                => $this->dataset_page->get_values(),
            'error_messages'        => $this->dataset_page->get_error_messages(),
            //
            'rs_page_tree'        => \app\model\recordset\page::fetch_tree(),
            'rs_media'            => \app\model\recordset\media::fetch_all_page($this->dataset_page->get_value('page_id')),
            'rs_category_tree'    => \app\model\recordset\category::fetch_tree(),
            'rs_template'         => \app\model\recordset\template::fetch_all(),
        );
        if (isset($this->composer_blocks))
        {
            $vars['composer_blocks'] = $this->composer_blocks;
        }
        array_unshift
        (
            $vars['rs_page_tree'],
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
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
        $this->display('composer');
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_entry($params)
    {
        $this->dataset_page->set_value('parent_page_id', isset($params[0]) ? $params[0] : 0);
        $this->dataset_page->set_value('composer_key',   isset($params[1]) ? $params[1] : 'default');
        // コンポーザー・ブロック情報取得
        $this->composer_blocks = $this->dataset_page->fetch_composer_block();
        //
        $this->display('edit');
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
        $this->dataset_page->post();
        $this->composer_blocks = $this->dataset_page->fetch_composer_block();
        foreach($this->composer_blocks as $v)
        {
            $v->block_post();
        }
        $is_valid = $this->dataset_page->check();
        foreach($this->composer_blocks as $v)
        {
            if ($v->block_check() == false)
            {
                $is_valid = false;
            }
        }
        if ($is_valid)
        {
            $this->dataset_page->create();
            //
            foreach($this->composer_blocks as $v)
            {
                $v->set_page_id($this->dataset_page->get_value('page_id'));
                $v->create();
            }
            response::redirect(url::create('/admin/page/summary', array($this->dataset_page->get_value('parent_page_id'))));
        }
        else 
        {
            $this->display('edit');
        }
    }
    // ********************************************************************************
    // **** 表示
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    public function display($template_name = 'edit')
    {
        $vars = array
        (
            'is_valid'          => $this->dataset_page->is_valid,
            'values'            => $this->dataset_page->get_values(),
            'error_messages'    => $this->dataset_page->get_error_messages(),
            //
            'rs_composer'       => \app\model\recordset\composer::fetch_all(),
            'rs_page_tree'      => \app\model\recordset\page::fetch_tree(),
            'rs_category_tree'  => \app\model\recordset\category::fetch_tree(),
            'rs_template'       => \app\model\recordset\template::fetch_all(),
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
        echo $this->render('controller/admin/page/create/'.$template_name.'.twig', $vars);
    }
}
<?php
namespace app\cscms\controller;

use \core\auth;
use \core\config;
use \core\debug;
use \core\globalvars;
use \core\view;

// dataset
use \app\cscms\model\controller\page as dataset_page;
use \app\cscms\model\helper\page as helper_page;

class page extends \app\controller
{
    private $dataset_page;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        $this->dataset_page = new dataset_page();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        // ページの読み込みチェック
        $this->dataset_page->set_value('page_id', \app\cscms\model\helper\page::get_current_page_id());
        if (!$this->dataset_page->read())
        {
            $this->display('404');
            return false;
        }
        globalvars::set_value('APP_CMS_PAGE', $this->dataset_page->get_values());
        // ------------------------------------------------------------
        // 認証チェック
        // ------------------------------------------------------------
        if (auth::check())
        {
            $this->display();
        }
        else
        {
            if (\app\cscms\model\helper\page::is_visible($this->dataset_page->get_values()))
            {
                $this->display();
            }
            else
            {
                $this->display('404');
            }
        }
    }
    // ********************************************************************************
    // **** 表示
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    public function display($template_name = '')
    {
        switch ($template_name)
        {
            case '404':
                header("HTTP/1.0 404 Not Found");
                echo $this->render('cscms/controller/page/404.twig');
                break;
            default:
                $rs = $this->dataset_page->get_composer_block();
                $composer_block_vars = array();
                foreach($rs as $v)
                {
                    $block_name = '\\app\\cscms\\block\\'.$v['block_key'].'\\controller';
                    $composer_block = new $block_name($this->dataset_page->get_value('page_id'), $v['composer_block_key']);
                    $composer_block_vars[$v['composer_block_key']] = $composer_block->body_view_html();
                }
                $composer_block_view = new view();
                $composer_html = '';
                if ($this->dataset_page->get_value('composer_output_mode') == 1)
                {
                    foreach($composer_block_vars as $v)
                    {
                        $composer_html .= $v;
                    }
                }
                else
                {
                    $composer_template = $this->dataset_page->get_value('composer_template');
                    $composer_html = $composer_block_view->render($composer_template, $composer_block_vars, 2);
                }
                //
                $vars = array
                (
                    'composer_html' => $composer_html,
                    'values'        => $this->dataset_page->get_values(),
                );
                $vars['extends_template'] = 'cscms/controller/page/template/'.$vars['values']['template_key'].'.twig';
                echo $this->render('cscms/controller/page/default.twig', $vars);
                break;
        }
    }
}
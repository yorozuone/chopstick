<?php
namespace app\controller;

use \core\auth;
use \core\config;
use \core\debug;
use \core\globalvars;
use \core\view;

// dataset
use \app\model\cms_page as dset_page;
use \app\model\helper\page as helper_page;

class cms_page extends \app\controller
{
    private $dset_page;
    // ********************************************************************************
    // **** アクション
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
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        // ページの読み込みチェック
        $this->dset_page->set_value('page_id', \app\model\helper\page::get_current_page_id());
        if (!$this->dset_page->read())
        {
            $this->display('404');
            return false;
        }
        globalvars::set_value('APP_CMS_PAGE', $this->dset_page->get_values());
        // ------------------------------------------------------------
        // 認証チェック
        // ------------------------------------------------------------
        if (auth::check())
        {
            $this->display();
        }
        else
        {
            if (\app\model\helper\page::is_visible($this->dset_page->get_values()))
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
                echo $this->render('controller/cms_page/404.twig');
                break;
            default:
                $rs = $this->dset_page->get_composer_block();
                $composer_block_vars = array();
                foreach($rs as $v)
                {
                    $block_name = '\\app\\block\\'.$v['block_key'];
                    $composer_block = new $block_name($this->dset_page->get_value('page_id'), $v['composer_block_key']);
                    $composer_block_vars[$v['composer_block_key']] = $composer_block->get_view_html();
                }
                $composer_block_view = new view();
                //
                $composer_template = $this->dset_page->get_composer_template();
                //
                // composer_html が指定されていなかったら、そのまま並べる
                //
                $composer_html = '';
                if ($composer_template == '')
                {
                    foreach($composer_block_vars as $v)
                    {
                        $composer_html .= $v;
                    }
                }
                else
                {
                    $composer_html = $composer_block_view->render($composer_template, $composer_block_vars, 2);
                }
                //
                $vars = array
                (
                    'composer_html'     => $composer_html,
                    'values'            => $this->dset_page->get_values(),
                );
                $vars['extends_template'] = 'theme/default/'.$vars['values']['template_key'].'.twig';

                echo $this->render('controller/cms_page/index.twig', $vars);
                break;
        }
    }
}
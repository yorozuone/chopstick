<?php
namespace app\twig\ext_function\classes;

use \core\db;
use \core\globalvars;
use \core\view;

class cs_title
{
    // --------------------------------------------------------------------------------
    // タイトルを表示
    // --------------------------------------------------------------------------------
    public static function render($config = array())
    {
        $config = is_array($config) ? $config : array();
        $config = array_replace_recursive
        (
            array
            (
                'id'                => 'cs_title',
                'class'             => 'cs_title',
                'template'          => 'default',
                'caption'           => '',
            ),
            $config
        );
        if ($config['caption'] == '')
        {
            $app_cms_page = globalvars::get_value('APP_CMS_PAGE');
            //
            $config['caption'] = $app_cms_page['page_title'];
        }
        //
        $vars = $config;
        //
        $v = new view();
        return $v->render('twig/ext_function/cs_title/'.$config['template'].'.twig', $vars);
    }
}
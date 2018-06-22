<?php
namespace app\twig\twig_function\cs_title;

use \core\db;
use \core\globalvars;
use \core\view;

class controller extends \app\twig_function
{
    // --------------------------------------------------------------------------------
    // タイトルを表示
    // --------------------------------------------------------------------------------
    public static function display($config = array())
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
        return self::render($config['template'], $vars);
    }
}
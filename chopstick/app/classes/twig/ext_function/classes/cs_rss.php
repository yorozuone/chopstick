<?php
namespace app\twig\ext_function\classes;

use \core\view;

class cs_rss
{
    // --------------------------------------------------------------------------------
    // render 実行
    // --------------------------------------------------------------------------------
    public static function render($config = array())
    {
        $config = is_array($config) ? $config : array();
        $config = array_replace_recursive
        (
            array
            (
                'id'        => 'cs_rss',
                'class'     => 'cs_rss',
                'template'  => 'default',
                'url'       => '',
            ),
            $config
        );
        if ($config['url'] == '')
        {
            $vars = array
            (
                'error_message' => 'RSS のURLが取得されていません。',
            );
            $vars = array_merge_recursive($config, $vars);
            return view::render('twig/ext_function/cs_rss/error.twig', $vars);
        }
        if (function_exists('simplexml_load_file') == false)
        {
            $vars = array
            (
                'error_message' => 'smplexml_load_file 関数が利用できません。',
            );
            $vars = array_merge_recursive($config, $vars);
            //
            $v = new view();
            return $v->render('twig/ext_function/cs_rss/error.twig', $vars);
        }
        $rss = simplexml_load_file($config['url']);
        $items = array();
        // ------------------------------------------------------------
        // ATOM
        // ------------------------------------------------------------
        if($rss->entry)
        {
            foreach($rss->entry as $v)
            {
                $obj = array
                (
                    'title' => (string)$v->title,
                    'link'  => (string)$v->link['href'],
                );
                $items[] = $obj;
            }
        }
        // ------------------------------------------------------------
        // RSS1.0
        // ------------------------------------------------------------
        elseif($rss->item)
        {
            foreach($rss->item as $v)
            {
                $obj = array
                (
                    'title' => (string)$v->title,
                    'link'  => (string)$v->link,
                );
                $items[] = $obj;
            }
        }
        // ------------------------------------------------------------
        // RSS2.0
        // ------------------------------------------------------------
        elseif($rss->channel->item)
        {
            foreach($rss->channel->item as $v)
            {
                $obj = array
                (
                    'title' => (string)$v->title,
                    'link'  => (string)$v->link,
                );
                $items[] = $obj;
            }
        }
        $vars = array
        (
            'items' => $items,
        );
        $vars = array_merge_recursive($config, $vars);
        //
        $v = new view();
        return $v->render('twig/ext_function/cs_rss/'.$config['template'].'.twig', $vars);
    }
}
<?php
namespace app\twig\ext_filter\classes;

use \core\view;

class cs_markdown
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function render($text, $config = array())
    {
        $config = is_array($config) ? $config : array();
        $config = array_merge
        (
            array
            (
                'id'        => 'cs_markdown',
                'class'     => 'cs_markdown',
                'template'  => 'default',
            ),
            $config
        );
        $Parsedown = new \app\twig\ext_filter\classes\cs_markdown_parsedown();
        $Parsedown->setBreaksEnabled(true);
        $Parsedown->setMarkupEscaped(false);
        $Parsedown->setUrlsLinked(false);
        $vars = array
        (
            'id'        => $config['id'],
            'class'     => $config['class'],
            'template'  => $config['template'],
            'html'      => $Parsedown->text($text),
        );
        $v = new view();
        return $v->render('twig/ext_filter/cs_markdown/'.$config['template'].'.twig', $vars);
    }
}
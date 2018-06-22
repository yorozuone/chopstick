<?php
namespace app\twig\twig_filter\cs_markdown;

use \core\view;

class controller extends \app\twig_filter
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function display($text)
    {
        $config = array
        (
            'id'        => 'cs_markdown',
            'class'     => 'cs_markdown',
            'template'  => 'default',
        );
        $Parsedown = new \app\twig\twig_filter\cs_markdown\cs_markdown_parsedown();
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
        return self::render($config['template'], $vars);
    }
}
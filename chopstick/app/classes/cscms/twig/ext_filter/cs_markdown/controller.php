<?php
namespace app\cscms\twig\ext_filter\cs_markdown;

use \core\view;

class controller extends \app\cscms\twig\ext_filter
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
        $Parsedown = new \app\cscms\twig\ext_filter\cs_markdown\cs_markdown_parsedown();
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
        $view = new view();
        return $view->render('/cscms/twig/ext_filter/cs_markdown/'.$config['template'].'.twig', $vars);
    }
}
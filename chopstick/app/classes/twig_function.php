<?php
namespace app;

use \core\file;
use \core\view;

class twig_function
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function render($template_name, $vars)
    {
        $path_1 = new \ReflectionClass(get_called_class());
        $path_2 = pathinfo($path_1->getFileName());
        $path_3 = file::path_join($path_2['dirname'], 'view');
        //
        $v = new view();
        $v->paths = $path_3;
        return $v->render($template_name.'.twig', $vars);
    }
}
<?php
namespace app;

use \core\config;
use \core\view;

class controller extends \core\controller
{
    //
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    //
    public function render($path='', $vars=array(), $mode=1)
    {
        $view = new view();
        return $view->render($path, $vars, $mode);
    }
}

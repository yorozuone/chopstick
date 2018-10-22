<?php
namespace app;

use \core\debug;
use \core\config;
use \core\view;

class controller extends \core\controller
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function render($path='', $vars=array(), $mode=1)
    {
        $application = array
        (
            'CS_APPLICATION_KEY'    => CS_APPLICATION_KEY,
            'CS_BASE_DIR'           => CS_BASE_DIR,
            'CS_HOME_DIR'           => CS_HOME_DIR,
            'CS_MODE'               => CS_MODE,
        );
        if (CS_MODE == 'development')
        {
            $application['debug_tarace'] = debug::dump(true);
        }
        $vars['application'] = $application;
        $view = new view();
        return $view->render($path, $vars, $mode);
    }
}

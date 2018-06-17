<?php
namespace app;

use \core\auth;
use \core\response;
use \core\url;

class controller_admin extends \app\controller
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();        
        //
        if (auth::check() == false)
        {
            response::redirect(url::create('/admin/auth/login'));
        }
    }
}
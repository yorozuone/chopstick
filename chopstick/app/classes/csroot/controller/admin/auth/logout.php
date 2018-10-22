<?php
namespace app\csroot\controller\admin\auth;

use \core\auth;
use \core\response;
use \core\url;

class logout extends \app\controller
{
    // ********************************************************************************
    // ****
    // **** アクション
    // ****
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    function action_index()
    {
        auth::logout();
        response::redirect(url::create('/csroot/admin/auth/login'));
    }
}

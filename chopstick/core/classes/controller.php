<?php
namespace core;

class controller
{
    public $controller_name = '';
    public $action_name = '';
    public $params = array();
    //
    // --------------------------------------------------------------------------------
    // 既定のアクション
    // --------------------------------------------------------------------------------
    public function before()
    {
        return true;
    }
    // --------------------------------------------------------------------------------
    // 既定のアクション
    // --------------------------------------------------------------------------------
    public function after()
    {
        return true;
    }
}

<?php
namespace app\controller;

use core\sqlbuilder\select;

class test
{
    public function action_index($params)
    {
        $s = new select();
        $s->from('cs_page')->select('name')->select('ipage')->where('ipage', '=', 26);
        $s->build();
        print_r($s->sql);
        print_r($s->params);
    }
}
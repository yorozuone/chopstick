<?php
namespace app\model\datasource;

use \core\file;

class template
{
    // --------------------------------------------------------------------------------
    // 表示用 template を取得
    // --------------------------------------------------------------------------------
    public static function fetch_all()
    {
        $rs_1 = array();
        //
        $rs_2 = file::get_list('views/theme/default');
        foreach($rs_2 as $v)
        {
            $obj = array();
            $obj['template_key'] = $v['filename'];
            $obj['caption'] = $v['filename'];
            $rs_1[] = $obj;
        }
        return $rs_1;
    }
}
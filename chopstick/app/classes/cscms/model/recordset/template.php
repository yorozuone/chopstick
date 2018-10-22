<?php
namespace app\cscms\model\recordset;

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
        $rs_2 = file::get_list('views/cscms/controller/page/template');
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
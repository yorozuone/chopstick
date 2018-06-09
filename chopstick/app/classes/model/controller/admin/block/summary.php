<?php
namespace app\model\controller\admin\block;

use \core\db;
use \core\file;

class summary extends \core\fieldset
{
    // ################################################################################
    // コンストラクタ
    // ################################################################################
    public function __construct()
    {
        parent::__construct();
    }
    // ################################################################################
    // レコードセット
    // ################################################################################
    public function fetch_all()
    {
        $con = new db();
        //
        $sql_1 = <<< EOT
SELECT
    block_key,
    name,
    description,
    install_version
FROM
    cs_block
ORDER BY
    order_at
EOT;
        $rs_1 = $con->query($sql_1);
        //
        foreach($rs_1 as $k => $v)
        {
            $rs_1[$k]['installed'] = true;
        }
        //
        $rs_2 = file::get_list('classes/block');
        foreach($rs_2 as $v)
        {
            if (array_search($v['filename'], array_column($rs_1, 'block_key')) === false)
            {
                $block_class = $v['section'].'\\block\\'.$v['filename'];
                if (class_exists($block_class))
                {
                    $obj = array();
                    $obj['block_key']   = $v['filename'];
                    $obj['name']        = $block_class::$block_name;
                    $obj['description'] = $block_class::$block_description;
                    $obj['version']     = $block_class::$block_version;
                    $obj['installed']   = false;
                    $rs_1[] = $obj;
                }
            }
        }
        return $rs_1;
    }
}
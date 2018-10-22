<?php
namespace app\cscms\model\controller\admin\block;

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
        $rs_2 = scandir(file::path_join(CS_BASE_DIR, 'app/classes/ext/cscms/block'));
        foreach($rs_2 as $v)
        {
            switch($v)
            {
                case '.':
                case '..':
                    break;
                default:
                    if (array_search($v, array_column($rs_1, 'block_key')) === false)
                    {
                        $block_class = '\\app\\ext\\cscms\\block\\'.$v.'\\controller';
                        if (class_exists($block_class))
                        {
                            $obj = array();
                            $obj['block_key']   = $v;
                            $obj['name']        = $block_class::$block_name;
                            $obj['description'] = $block_class::$block_description;
                            $obj['version']     = $block_class::$block_version;
                            $obj['installed']   = false;
                            $rs_1[] = $obj;
                        }
                    }
                    break;
            }
        }
        return $rs_1;
    }
}
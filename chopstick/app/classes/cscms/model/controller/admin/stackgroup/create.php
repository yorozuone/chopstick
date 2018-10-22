<?php
namespace app\cscms\model\controller\admin\stackgroup;

use \core\db;

class create extends \app\cscms\model\controller\admin\stackgroup\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 検証（作成）
    // --------------------------------------------------------------------------------
    public function check()
    {
        $this->validate('required', 'caption');
        //
        return $this->is_valid;
    }
    // ################################################################################
    // データ操作
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 作成
    // --------------------------------------------------------------------------------
    public function create()
    {
        $con = new db();
        //
        $sql = <<< EOT
INSERT INTO cs_stackgroup
(
    caption,
    created_at,
    updated_at
)
VALUE
(
    :caption,
    NOW(),
    NOW()
);
EOT;
        $sql_params = array
        (
            ':caption'  => $this->get_value('caption'),
        );
        //
        $con->query($sql, $sql_params);
    }
}
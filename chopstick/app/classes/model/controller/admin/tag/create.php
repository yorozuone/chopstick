<?php
namespace app\model\controller\admin\tag;

use \core\db;

class create extends \app\model\controller\admin\tag\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // ------------------------------------------------------------
    // 検証（作成）
    // ------------------------------------------------------------
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
    // データ操作（作成）
    // --------------------------------------------------------------------------------
    public function create()
    {
        $con = new db();
        //
        $sql_1 = <<< EOT
INSERT INTO cs_tag
(
    caption,
    created_at,
    updated_at
)
VALUES
(
    :caption,
    NOW(),
    NOW()
);
EOT;
        $sql_params_1 = array
        (
            ':caption' => $this->get_value('caption'),
        );
        $con->query($sql_1, $sql_params_1);
        //
        $sql_2 = <<< EOT
SELECT
    LAST_INSERT_ID() as tag_id;
EOT;
        $rs_2 = $con->query($sql_2);
        //
        $this->set_value('tag_id', $rs_2[0]['tag_id']);
    }
}
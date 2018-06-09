<?php
namespace app\model\controller\admin\tag;

use \core\db;

class edit extends \app\model\controller\admin\tag\base
{
    //
    // ################################################################################
    //
    // 検証
    //
    // ################################################################################
    //
    // ------------------------------------------------------------
    // 検証（更新）
    // ------------------------------------------------------------
    //
    public function check()
    {
        $this->validate('required', 'tag_id');
        $this->validate('required', 'caption');
        //
        return $this->is_valid;
    }
    //
    // ################################################################################
    //
    // データ操作
    //
    // ################################################################################
    //
    // --------------------------------------------------------------------------------
    // データ操作（更新）
    // --------------------------------------------------------------------------------
    //
    public function update()
    {
        $con = new db();
        //
        $sql = <<< EOT
UPDATE
    cs_tag
SET
    caption = :caption,
    updated_at = NOW()
WHERE
    tag_id = :tag_id;
EOT;
        $sql_params = array
        (
            ':tag_id'   => $this->get_value('tag_id'),
            ':caption'  => $this->get_value('caption'),
        );
        //
        $con->query($sql, $sql_params);
    }
}
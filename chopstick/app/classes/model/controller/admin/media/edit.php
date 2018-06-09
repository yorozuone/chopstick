<?php
namespace app\model\controller\admin\media;

use \core\db;

class edit extends \app\model\controller\admin\media\base
{
    //
    // ################################################################################
    //
    // 検証
    //
    // ################################################################################
    //
    // --------------------------------------------------------------------------------
    // 検証（更新）
    // --------------------------------------------------------------------------------
    //
    public function check()
    {
        return $this->is_valid;
    }
    //
    // ################################################################################
    //
    // データ操作
    //
    // ################################################################################
    //
    // ------------------------------------------------------------
    // 更新
    // ------------------------------------------------------------
    //
    public function update()
    {
        $con = new db();
        //
        $sql = <<< EOT
UPDATE
    cs_media
SET
    description = :description,
    updated_at = NOW()
WHERE
    media_id = :media_id;
EOT;
        $sql_params = array
        (
            ':media_id' => $this->get_value('media_id'),
            ':description' => $this->get_value('description'),
        );
        $con->query($sql, $sql_params);
    }
}

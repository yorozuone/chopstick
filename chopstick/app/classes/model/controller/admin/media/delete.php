<?php
namespace app\model\controller\admin\media;

use \core\db;

class delete extends \app\model\controller\admin\media\base
{
    //
    // ################################################################################
    //
    // 検証
    //
    // ################################################################################
    //
    // --------------------------------------------------------------------------------
    // 検証（削除）
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
    // 削除
    // ------------------------------------------------------------
    //
    public function delete()
    {
        $con = new db();
        //
        $sql = <<< EOT
DELETE
FROM
    cs_media
WHERE
    media_id = :media_id;
EOT;
        $sql_params = array
        (
            ':media_id' => $this->get_value('media_id'),
        );
        $rs = $con->query($sql, $sql_params);
        //
        return true;
    }
}

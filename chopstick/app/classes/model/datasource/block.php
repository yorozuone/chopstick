<?php
namespace app\model\datasource;

use \core\db;

class block
{
    // --------------------------------------------------------------------------------
    // 表示用 block を取得
    // --------------------------------------------------------------------------------
    public static function fetch_all()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    block_key,
    caption,
    description,
    created_at,
    updated_at
FROM
    cs_block
ORDER BY
    order_at
EOT;
        return $con->query($sql);
    }
}
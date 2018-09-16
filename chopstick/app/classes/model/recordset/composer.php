<?php
namespace app\model\recordset;

use \core\db;

class composer
{
    // --------------------------------------------------------------------------------
    // 表示用コンポーザー一覧
    // --------------------------------------------------------------------------------
    public static function fetch_all()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    composer_key,
    caption
FROM
    cs_composer
ORDER BY
    order_at
EOT;
        return $con->query($sql);
    }
}

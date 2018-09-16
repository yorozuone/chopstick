<?php
namespace app\model\recordset;

use \core\db;

class group
{
    // --------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------
    public static function fetch_all()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    group_key,
    caption,
    description,
    reserved,
    created_at,
    updated_at
FROM
    cs_group
ORDER BY
    order_at
EOT;
        return $con->query($sql);
    }
}
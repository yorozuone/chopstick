<?php
namespace app\model\recordset\cs;

use \core\db;

class roll
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
    roll_key,
    caption,
    description,
    reserved,
    created_at,
    updated_at
FROM
    cs_roll
ORDER BY
    order_at
EOT;
        return $con->query($sql);
    }
}
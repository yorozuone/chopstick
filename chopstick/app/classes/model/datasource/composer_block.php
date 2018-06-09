<?php
namespace app\model\datasource;

use \core\db;

class composer_block
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function fetch_all($composer_key)
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    block_key,
    composer_block_key,
    caption,
    description
FROM
    cs_composer_block
WHERE
    composer_key = :composer_key
ORDER BY
    order_at
EOT;
        $sql_params = array
        (
            ':composer_key' => $composer_key,
        );
        $rs = $con->query($sql, $sql_params);
        //
        return $rs;
    }
}
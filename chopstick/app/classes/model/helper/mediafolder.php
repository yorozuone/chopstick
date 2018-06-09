<?php
namespace app\model\helper;

use \core\db;
use \core\route;

class mediafolder
{
    public static function get_caption($mediafolder_id)
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    caption
FROM
    cs_mediafolder
WHERE
    mediafolder_id = :mediafolder_id;
EOT;
        $params = array
        (
            'mediafolder_id' => $mediafolder_id,
        );
        $rs = $con->query($sql, $params);
        //
        return isset($rs[0]['caption']) ? $rs[0]['caption'] : '';
    }
}
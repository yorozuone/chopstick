<?php
namespace app\model\controller\admin;

use \core\db;

class menu extends \core\fieldset
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function fetch_disabled_page()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    page_id,
    page_type,
    page_title,
    external_link,
    updated_at
FROM
    cs_page
WHERE
    publish_status = -2 OR
    publish_status = -1 OR
    publish_type = 0 OR
    CASE WHEN publish_type = 2 AND publish_start Is Not Null AND publish_start Is Null THEN publish_start <= :current_datetime_1 END OR
    CASE WHEN publish_type = 2 AND publish_start Is Null AND publish_start Is Not Null THEN publish_end >= :current_datetime_2 END OR
    CASE WHEN publish_type = 2 AND publish_start Is Not Null AND publish_start Is Not Null THEN publish_start <= :current_datetime_3 OR publish_end >= :current_datetime_4 END
ORDER BY
    updated_at desc
EOT;
        $current_date = date('Y/m/d H:i:s');
        $sql_params = array
        (
            ':current_datetime_1' => $current_date,
            ':current_datetime_2' => $current_date,
            ':current_datetime_3' => $current_date,
            ':current_datetime_4' => $current_date,
        );
        $rs = $con->query($sql, $sql_params);
        //
        return $rs;
    }
}
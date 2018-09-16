<?php
namespace app\model\recordset;

use \core\db;

class media
{
    // --------------------------------------------------------------------------------
    // 表示用 media を取得 ( mediafolder_id )
    // --------------------------------------------------------------------------------
    public static function fetch_all_mediafolder($mediafolder_id)
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    media_id,
    mediafolder_id,
    folder_name,
    file_name,
    mime_type,
    description,
    updated_at
FROM
    cs_media
WHERE
    mediafolder_id = :mediafolder_id
ORDER BY
    file_name
EOT;
        $sql_params = array
        (
            ':mediafolder_id' => $mediafolder_id,
        );
        return $con->query($sql, $sql_params);
    }
    // --------------------------------------------------------------------------------
    // 表示用 media を取得 ( page_id )
    // --------------------------------------------------------------------------------
    public static function fetch_all_page($page_id)
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    media_id,
    page_id,
    folder_name,
    file_name,
    mime_type,
    description,
    updated_at
FROM
    cs_media
WHERE
    page_id = :page_id
ORDER BY
    file_name
EOT;
        $sql_params = array
        (
            ':page_id' => $page_id,
        );
        return $con->query($sql, $sql_params);
    }
    // --------------------------------------------------------------------------------
    // 表示用 media を取得 ( stack_key )
    // --------------------------------------------------------------------------------
    public static function fetch_all_stack($stack_key)
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    media_id,
    stack_key,
    folder_name,
    file_name,
    mime_type,
    description,
    updated_at
FROM
    cs_media
WHERE
    stack_key = :stack_key
ORDER BY
    file_name
EOT;
        $sql_params = array
        (
            ':stack_key' => $this->stack_key,
        );
        return $con->query($sql, $sql_params);
    }
}
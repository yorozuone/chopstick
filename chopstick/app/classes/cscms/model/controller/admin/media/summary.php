<?php
namespace app\cscms\model\controller\admin\media;

use \core\db;

class summary extends \core\fieldset
{
    // --------------------------------------------------------------------------------
    // コンストラクタ
    // --------------------------------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
        //
        $this->append('mediafolder_id',  'フォルダーId');
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function fetch_all()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    cs_media.media_id,
    cs_media.description,
    cs_media.updated_at
FROM
    cs_media
JOIN
    cs_mediafolder
ON
    cs_media.mediafolder_id = cs_mediafolder.mediafolder_id
WHERE
    cs_media.mediafolder_id = :mediafolder_id
EOT;
        $sql_params = array
        (
            ':mediafolder_id' => $this->get_value('mediafolder_id'),
        );
        return $con->query($sql, $sql_params);
    }
}
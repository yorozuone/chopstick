<?php
namespace app\model\controller\admin\tag;

use \core\db;

class summary extends \core\fieldset
{
    //
    // ################################################################################
    //
    // コンストラクタ
    //
    // ################################################################################
    //
    public function __construct()
    {
        parent::__construct();
    }
    //
    // ################################################################################
    //
    // レコードセット
    //
    // ################################################################################
    //
    public function fetch_all()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    tag_id,
    caption,
    created_at,
    updated_at
FROM
    cs_tag
ORDER BY
    caption
EOT;
        return $con->query($sql);
    }
}
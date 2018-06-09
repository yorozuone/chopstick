<?php
namespace app\model\controller\admin\mediafolder;

use \core\db;

class summary extends \core\fieldset
{
    // --------------------------------------------------------------------------------
    // コンストラクタ
    // --------------------------------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
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
    mediafolder_id,
    caption,
    order_at,
    created_at,
    updated_at
FROM
    cs_mediafolder
ORDER BY
    caption
EOT;
        return $con->query($sql);
    }
}
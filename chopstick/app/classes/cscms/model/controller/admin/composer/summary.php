<?php
namespace app\cscms\model\controller\admin\composer;

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
    composer_key,
    caption,
    created_at,
    updated_at
FROM
    cs_composer
ORDER BY
    order_at
EOT;
        return $con->query($sql);
    }
    // --------------------------------------------------------------------------------
    // 並び変え
    // --------------------------------------------------------------------------------
    public function sort($rs)
    {
        $con = new db();
        //
        $sql = <<< EOT
UPDATE
    cs_composer
SET
    order_at = :order_at
WHERE
    composer_key = :composer_key
EOT;
        $i = 0;
        foreach($rs as $v)
        {
            $i++;
            $sql_params = array
            (
                ':order_at' => $i,
                ':composer_key'  => $v
            );
            $con->query($sql, $sql_params);
        }
    }
}
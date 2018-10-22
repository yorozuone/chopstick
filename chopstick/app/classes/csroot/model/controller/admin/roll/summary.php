<?php
namespace app\model\controller\admin\roll;

use \core\db;

class summary extends \core\fieldset
{
    // ################################################################################
    // コンストラクタ
    // ################################################################################
    public function __construct()
    {
        parent::__construct();
        //
        $this->append('roll_key',   'ロール・キー');
    }
    // ################################################################################
    // レコードセット
    // ################################################################################
    public function fetch_all()
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
    cs_roll.order_at
EOT;
        return $con->query($sql);
    }
    // --------------------------------------------------------------------------------
    // 並び順
    // --------------------------------------------------------------------------------
    public function sort($rs)
    {
        $con = new db();
        //
        $sql = <<< EOT
UPDATE
    cs_roll
SET
    order_at = :order_at
WHERE
    roll_key = :roll_key
EOT;
        $i = 0;
        foreach($rs as $v)
        {
            $i++;
            $sql_params = array
            (
                ':roll_key'    => $v,
                ':order_at'     => $i,
            );
            $con->query($sql, $sql_params);
        }
    }
}
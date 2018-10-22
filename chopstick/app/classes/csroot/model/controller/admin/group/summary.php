<?php
namespace app\model\controller\admin\group;

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
        $this->append('group_key',  'グループ・キー');
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
    group_key,
    caption,
    description,
    reserved,
    created_at,
    updated_at
FROM
    cs_group
ORDER BY
    cs_group.order_at
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
    cs_group
SET
    order_at = :order_at
WHERE
    group_key = :group_key
EOT;
        $i = 0;
        foreach($rs as $v)
        {
            $i++;
            $sql_params = array
            (
                ':group_key'    => $v,
                ':order_at'     => $i,
            );
            $con->query($sql, $sql_params);
        }
    }
}
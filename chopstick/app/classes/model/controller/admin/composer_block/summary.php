<?php
namespace app\model\controller\admin\composer_block;

use \core\db;

class summary extends \core\fieldset
{
    //
    // --------------------------------------------------------------------------------
    // コンストラクタ
    // --------------------------------------------------------------------------------
    //
    public function __construct()
    {
        parent::__construct();
        //
        $this->append('composer_key', 'コンポーザーKey');
    }
    //
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    //
    public function fetch_all()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    composer_key,
    composer_block_key,
    block_key,
    caption,
    description,
    datasource,
    order_at,
    created_at,
    updated_at
FROM
    cs_composer_block
WHERE
    composer_key = :composer_key
ORDER BY
    order_at
EOT;
        $sql_params = array
        (
            ':composer_key' => $this->get_value('composer_key'),
        );
        return $con->query($sql, $sql_params);
    }
    //
    // --------------------------------------------------------------------------------
    // 並び順
    // --------------------------------------------------------------------------------
    //
    public function sort($rs)
    {
        $con = new db();
        //
        $sql = <<< EOT
UPDATE
    cs_composer_block
SET
    order_at = :order_at
WHERE
    block_key = :block_key
EOT;
        $i = 0;
        foreach($rs as $v)
        {
            $i++;
            $sql_params = array
            (
                ':order_at' => $i,
                ':block_key'  => $v
            );
            $con->query($sql, $sql_params);
        }
    }
}
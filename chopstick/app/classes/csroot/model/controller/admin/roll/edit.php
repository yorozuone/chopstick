<?php
namespace app\model\controller\admin\roll;

use \core\db;

class edit extends \app\csroot\model\controller\admin\roll\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 検証（更新）
    // --------------------------------------------------------------------------------
    public function check()
    {
        $this->validate('required', 'roll_key');
        $this->validate('required', 'caption');
        return $this->is_valid;
    }
    // ################################################################################
    // データ操作
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 更新
    // --------------------------------------------------------------------------------
    public function update()
    {
        $con = new db();
        //
        $sql = <<< EOT
UPDATE
    cs_roll
SET
    caption = :caption,
    description = :description,
    updated_at = NOW()
WHERE
    roll_key = :roll_key;
EOT;
        $sql_params = array
        (
            ':caption'      => $this->get_value('caption'),
            ':description'  => $this->get_value('description'),
            //
            ':roll_key'    => $this->get_value('roll_key'),
        );
        $con->query($sql, $sql_params);
    }
}
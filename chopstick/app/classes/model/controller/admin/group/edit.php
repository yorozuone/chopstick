<?php
namespace app\model\controller\admin\group;

use \core\db;

class edit extends \app\model\controller\admin\group\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 検証（更新）
    // --------------------------------------------------------------------------------
    public function check()
    {
        $this->validate('required', 'group_key');
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
    cs_group
SET
    caption = :caption,
    description = :description,
    updated_at = NOW()
WHERE
    group_key = :group_key;
EOT;
        $sql_params = array
        (
            ':caption'      => $this->get_value('caption'),
            ':description'  => $this->get_value('description'),
            //
            ':group_key'    => $this->get_value('group_key'),
        );
        $con->query($sql, $sql_params);
        //
        $sql_2 = <<< EOT
DELETE
FROM
    cs_group_roll
WHERE
    group_key = :group_key AND
    reserved = 0;
EOT;
        $sql_params_2 = array
        (
            ':group_key' => $this->get_value('group_key'),
        );
        $con->query($sql_2, $sql_params_2);
        //
        $sql_3 = <<< EOT
INSERT INTO cs_group_roll
(
    group_key,
    roll_key,
    reserved,
    created_at,
    updated_at
)
VALUE
(
    :group_key,
    :roll_key,
    0,
    NOW(),
    NOW()
);
EOT;
        foreach($this->get_value('roll_keys') as $v)
        {
            $sql_params_3 = array
            (
                ':group_key'        => $this->get_value('group_key'),
                ':roll_key'         => $v,
            );
            $con->query($sql_3, $sql_params_3);
        }
    }
}
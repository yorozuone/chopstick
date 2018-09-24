<?php
namespace app\model\controller\admin\composer;

use \core\db;

class create extends \app\model\controller\admin\composer\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 検証（作成）
    // --------------------------------------------------------------------------------
    public function check()
    {
        $this->validate('required', 'composer_key');
        $this->validate('alnum',    'composer_key', array('-', '_'));
        $this->validate('required', 'caption');
        $this->validate('required', 'composer_output_mode');
        $this->validate('required', 'template_key');
        //
        return $this->is_valid;
    }
    // ################################################################################
    // データ操作
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 作成
    // --------------------------------------------------------------------------------
    public function create()
    {
        $con = new db();
        //
        $sql_1 = <<< EOT
SELECT
    MAX(order_at) AS order_at
FROM
    cs_composer;
EOT;
        $rs_1 = $con->query($sql_1);
        $order_at = isset($rs_1[0]['order_at']) ? $rs_1[0]['order_at'] : -1;
        $order_at += 1;
        //
        $sql_2 = <<< EOT
INSERT INTO cs_composer
(
    composer_key,
    caption,
    template_key,
    composer_output_mode,
    composer_template,
    created_at,
    updated_at
)
VALUE
(
    :composer_key,
    :caption,
    :template_key,
    :composer_output_mode,
    :composer_template,
    NOW(),
    NOW()
);
EOT;
        $sql_params_2 = array
        (
            ':composer_key'         => $this->get_value('composer_key'),
            ':caption'              => $this->get_value('caption'),
            ':template_key'         => $this->get_value('template_key'),
            ':composer_output_mode' => $this->get_value('composer_output_mode'),
            ':composer_template'    => $this->get_value('composer_template'),
            ':order_at'             => $order_at,
        );
        //
        $con->query($sql_2, $sql_params_2);
    }
}
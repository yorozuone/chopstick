<?php
namespace app\model\controller\admin\composer;

use \core\db;

class edit extends \app\model\controller\admin\composer\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 検証（更新）
    // --------------------------------------------------------------------------------
    public function check()
    {
        $this->validate('required', 'composer_key');
        $this->validate('alnum',    'composer_key', array('-', '_'));
        $this->validate('required', 'caption');
        //
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
    cs_composer
SET
    caption = :caption,
    template_key = :template_key,
    composer_output_mode = :composer_output_mode,
    composer_template = :composer_template,
    updated_at = NOW()
WHERE
    composer_key = :composer_key;
EOT;
        $sql_params = array
        (
            ':composer_key'         => $this->get_value('composer_key'),
            ':caption'              => $this->get_value('caption'),
            ':template_key'         => $this->get_value('template_key'),
            ':composer_output_mode' => $this->get_value('composer_output_mode'),
            ':composer_template'    => $this->get_value('composer_template'),
        );
        //
        $con->query($sql, $sql_params);
    }    
}
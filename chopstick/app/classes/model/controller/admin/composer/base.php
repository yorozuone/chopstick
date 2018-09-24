<?php
namespace app\model\controller\admin\composer;

use \core\db;

class base extends \core\fieldset
{
    // ################################################################################
    // コンストラクタ
    // ################################################################################
    public function __construct()
    {
        parent::__construct();
        //
        $this->append('composer_key',           'コンポーザー・キー');
        //
        $this->append('caption',                'コンポーザー名');
        $this->append('template_key',           'テンプレート・キー',   'default');
        $this->append('composer_template',      'コンポーザー・テンプレート');
        $this->append('composer_output_mode',   '出力方法', 1);
        $this->append('order_at',               '並び順');
    }
    // ################################################################################
    // データ操作
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 読込
    // --------------------------------------------------------------------------------
    public function read()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    composer_key,
    caption,
    template_key,
    composer_output_mode,
    composer_template
FROM
    cs_composer
WHERE
    composer_key = :composer_key;
EOT;
        $sql_params = array
        (
            ':composer_key' => $this->get_value('composer_key'),
        );
        $rs = $con->query($sql, $sql_params);
        //
        if (isset($rs[0]) == false)
        {
            return false;
        }
        $this->set_values($rs[0]);
        return true;
    }
}
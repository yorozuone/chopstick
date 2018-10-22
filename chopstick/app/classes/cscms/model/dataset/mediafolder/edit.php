<?php
namespace app\cscms\model\dataset\mediafolder;

use \core\db;

class edit extends \app\cscms\model\dataset\mediafolder\base
{
    // ################################################################################
    // コンストラクタ
    // ################################################################################
    public function __construct()
    {
        parent::__construct();
        //
        $this->append('mediafolder_id', 'メディアフォルダid');
        //
        $this->append('caption',        'フォルダ名');
    }
    // ################################################################################
    // 検証
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 検証（更新）
    // --------------------------------------------------------------------------------
    public function check()
    {
        $this->validate('required', 'mediafolder_id');
        $this->validate('required', 'caption');
        return $this->is_valid;
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
    mediafolder_id,
    caption
FROM
    cs_mediafolder
WHERE
    mediafolder_id = :mediafolder_id;
EOT;
        $sql_params = array
        (
            ':mediafolder_id' => $this->get_value('mediafolder_id'),
        );
        $rs = $con->query($sql, $sql_params);
        //
        if (isset($rs[0]) == false)
        {
            return false;
        }
        $this->set_values($rs[0]);
        //
        return true;
    }
    // --------------------------------------------------------------------------------
    // 更新
    // --------------------------------------------------------------------------------
    public function update()
    {
        $con = new db();
        //
        $sql = <<< EOT
UPDATE
    cs_mediafolder
SET
    caption = :caption,
    updated_at = NOW()
WHERE
    mediafolder_id = :mediafolder_id;
EOT;
        $sql_params = array
        (
            ':mediafolder_id'   => $this->get_value('mediafolder_id'),
            ':caption'          => $this->get_value('caption'),
        );
        //
        $con->query($sql, $sql_params);
    }
}
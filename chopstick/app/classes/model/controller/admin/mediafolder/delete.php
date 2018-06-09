<?php
namespace app\model\controller\admin\mediafolder;

use \core\db;

class delete extends \core\fieldset
{
    //
    // ################################################################################
    //
    // コンストラクタ
    //
    // ################################################################################
    //
    public function __construct()
    {
        parent::__construct();
        //
        $this->append('mediafolder_id', 'フォルダid');
        //
        $this->append('caption',        'フォルダ名');
    }
    //
    // ################################################################################
    //
    // 検証
    //
    // ################################################################################
    //
    // --------------------------------------------------------------------------------
    // 検証（削除）
    // --------------------------------------------------------------------------------
    //
    public function check()
    {
        return $this->is_valid;
    }
    //
    // ################################################################################
    //
    // データ操作
    //
    // ################################################################################
    //
    // --------------------------------------------------------------------------------
    // 読込
    // --------------------------------------------------------------------------------
    //
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
    //
    // --------------------------------------------------------------------------------
    // 削除
    // --------------------------------------------------------------------------------
    //
    public function delete()
    {
        $con = new db();
        //
        $sql = <<< EOT
DELETE
FROM
    cs_mediafolder
WHERE
    mediafolder_id = :mediafolder_id;
EOT;
        $sql_params = array
        (
            ':mediafolder_id' => $this->get_value('mediafolder_id'),
        );
        //
        $con->query($sql, $sql_params);
    }
}
<?php
namespace app\model\controller\admin\tag;

use \core\db;

class base extends \core\fieldset
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
        $this->append('tag_id',     'タグid');
        //
        $this->append('caption',    'タグ名');
    }
    //
    // ################################################################################
    //
    // データ操作
    //
    // ################################################################################
    //
    // --------------------------------------------------------------------------------
    // データ操作（読込）
    // --------------------------------------------------------------------------------
    //
    public function read()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    tag_id,
    caption
FROM
    cs_tag
WHERE
    tag_id = :tag_id;
EOT;
        $sql_params = array
        (
            ':tag_id' => $this->get_value('tag_id'),
        );
        $rs = $con->query($sql, $sql_params);
        //
        if (!isset($rs[0]))
        {
            return false;
        }        
        $this->set_values($rs[0]);
        return true;
    }
}
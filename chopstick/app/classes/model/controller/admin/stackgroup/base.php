<?php
namespace app\model\controller\admin\stackgroup;

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
        $this->append('stackgroup_id',  'スタック・グループid');
        //
        $this->append('caption',        'スタック・グループ名');
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
    stackgroup_id,
    caption
FROM
    cs_stackgroup
WHERE
    stackgroup_id = :stackgroup_id;
EOT;
        $sql_params = array
        (
            ':stackgroup_id' => $this->get_value('stackgroup_id'),
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
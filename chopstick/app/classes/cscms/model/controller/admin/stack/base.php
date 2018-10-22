<?php
namespace app\cscms\model\controller\admin\stack;

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
        $this->append('stack_key',          'スタック・キー');
        //
        $this->append('stackgroup_id',      'スタック・グループid');
        $this->append('content',            'スタック');
        $this->append('description',        '説明');
        $this->append('publish_status',     '状態', -2);
        $this->append('publish_type',       '公開設定', 1);
        $this->append('publish_start',      '開始日時');
        $this->append('publish_end',        '終了日時');
        $this->append('reserved',           '予約', 0);
        $this->append('order_at',           '並び順', 0);
        //
        $this->append('stackgroup_caption', 'スタック・グループ名');
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
    cs_stack.stack_key,
    cs_stack.stackgroup_id,
    t1.caption as stackgroup_caption,
    cs_stack.content,
    cs_stack.description,
    cs_stack.publish_status,
    cs_stack.publish_type,
    cs_stack.publish_start,
    cs_stack.publish_end,
    cs_stack.created_at,
    cs_stack.updated_at
FROM
    cs_stack
LEFT JOIN
    cs_stackgroup as t1
ON
    cs_stack.stackgroup_id = t1.stackgroup_id
WHERE
    cs_stack.stack_key = :stack_key;
EOT;
        $sql_params = array
        (
            ':stack_key' => $this->get_value('stack_key'),
        );
        $rs = $con->query($sql, $sql_params);
        if (!isset($rs[0]))
        {
            return false;
        }
        $this->set_values($rs[0]);
        return true;
    }
    // --------------------------------------------------------------------------------
    // stackgroup
    // --------------------------------------------------------------------------------
    public static function fetch_stackgroup()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    stackgroup_id,
    caption
FROM
    cs_stackgroup
ORDER BY
    order_at,
    stackgroup_id
EOT;
        return $con->query($sql);
    }
}
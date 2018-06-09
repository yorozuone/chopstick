<?php
namespace app\model\controller\admin\category;

use \core\db;

class edit extends \app\model\controller\admin\category\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 検証（更新）
    // --------------------------------------------------------------------------------
    public function check()
    {
        $this->validate('required', 'parent_category_id');
        $this->validate('required', 'caption');
        $this->validate('required', 'permanent_name');
        $this->validate('alnum',    'permanent_name', array('-', '_'));
        //
        if ($this->is_edit_permanent_name() == false)
        {
            $this->set_error('permanent_name', 'パーマネント名は、ほかのページで使われています。');
        }
        // 親カテゴリID検証
        if ($this->is_edit_parent_category_id() == false)
        {
            $this->set_error('parent_category_id', 'カテゴリの階層構造がループしています。親カテゴリを見直してください。');
        }
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
    cs_category
SET
    parent_category_id = :parent_category_id,
    caption = :caption,
    permanent_name = :permanent_name,
    updated_at = NOW()
WHERE
    category_id = :category_id;
EOT;
        $sql_params = array
        (
            ':category_id'          => $this->get_value('category_id'),
            ':parent_category_id'   => $this->get_value('parent_category_id'),
            ':caption'              => $this->get_value('caption'),
            ':permanent_name'       => $this->get_value('permanent_name'),
        );
        //
        $con->query($sql, $sql_params);
        //
        return true;
    }
    // ################################################################################
    // ヘルパー
    // ################################################################################
    // --------------------------------------------------------------------------------
    // permanent_name が編集可能か検証する
    // --------------------------------------------------------------------------------
    public function is_edit_permanent_name()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    COUNT(*) AS CNT
FROM
    cs_category
WHERE
    category_id <> :category_id AND
    permanent_name = :permanent_name
EOT;
        $sql_params = array
        (
            ':category_id' => $this->get_value('category_id'),
            ':permanent_name' => $this->get_value('permanent_name'),
        );
        //
        $rs1 = $con->query($sql, $sql_params);
        //
        return ($rs1[0]['CNT'] == 0) ? true : false;
    }
    // --------------------------------------------------------------------------------
    // **** 指定した親 category からのツリー構造がループしていないか検証
    // --------------------------------------------------------------------------------
    public function is_edit_parent_category_id()
    {
        $category_id = $this->get_value('category_id');
        $parent_category_id = $this->get_value('parent_category_id');
        //
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    category_id,
    parent_category_id
FROM
    cs_category
EOT;
        $rs_src = $con->query($sql);
        //
        $rs_key = array_search($category_id, array_column($rs_src, 'category_id'));
        $rs_src[$rs_key]['parent_category_id'] = $parent_category_id;
        //
        $rs_dst = array();
        //
        $is_loop = false;
        $this->recursion_is_edit_parent_category_id($rs_src, $parent_category_id, $rs_dst, $is_loop);
        //
        return !$is_loop;
    }
    // ----------
    // 指定した parent_category からのツリー構造がループしていないか検査（再帰）
    // ----------
    private function recursion_is_edit_parent_category_id($rs_src, $parent_category_id, &$rs_dst, &$is_loop)
    {
        foreach($rs_src as $src)
        {
            if ($is_loop == true)
            {
                return;
            }
            if ($src['parent_category_id'] == $parent_category_id)
            {
                if (!isset($rs_dst[$src['category_id']]))
                {
                    $rs_dst[$src['category_id']] = 0;
                }
                $rs_dst[$src['category_id']] += 1;
                if ($rs_dst[$src['category_id']] > 1)
                {
                    $is_loop = true;
                }
                $this->recursion_is_edit_parent_category_id($rs_src, $src['category_id'], $rs_dst, $is_loop);
            }
        }
    }
}
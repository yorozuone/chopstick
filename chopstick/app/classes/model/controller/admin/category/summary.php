<?php
namespace app\model\controller\admin\category;

use \core\db;

class summary extends \core\fieldset
{
    //
    // --------------------------------------------------------------------------------
    // コンストラクタ
    // --------------------------------------------------------------------------------
    //
    public function __construct()
    {
        parent::__construct();
        //
        $this->append('parent_category_id', '親カテゴリーId');
    }
    //
    // --------------------------------------------------------------------------------
    // 表示用のカテゴリ一覧取得
    // --------------------------------------------------------------------------------
    //
    public function fetch_all()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    category_id,
    parent_category_id,
    caption,
    created_at,
    updated_at
FROM
    cs_category
WHERE
    parent_category_id = :parent_category_id
ORDER BY
    order_at,
    category_id
EOT;
        $sql_params = array
        (
            ':parent_category_id' => $this->get_value('parent_category_id'),
        );
        //
        return $con->query($sql, $sql_params);
    }
    //
    // --------------------------------------------------------------------------------
    // 表示用 category_tree を取得
    // --------------------------------------------------------------------------------
    //
    public function fetch_tree()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    category_id,
    parent_category_id,
    caption,
    order_at
FROM
    cs_category
ORDER BY
    order_at
EOT;
        $rs_src = $con->query($sql);
        $rs_dst = array();
        $this->recursion_fetch_tree($rs_src, 0, $rs_dst, 1);
        return $rs_dst;
    }
    //
    // --------------------------------------------------------------------------------
    // 表示用 category_tree を取得（再帰）
    // --------------------------------------------------------------------------------
    //
    public function recursion_fetch_tree($rs_src, $parent_category_id, &$rs_dst, $hierarchy=1)
    {
        foreach($rs_src as $src)
        {
            if ($src['parent_category_id'] == $parent_category_id)
            {
                $obj = $src;
                $obj['tree_caption'] = '└'.str_repeat('─', $hierarchy-1).' '.$src['caption'];
                $rs_dst[] = $obj;
                self::recursion_fetch_tree($rs_src, $src['category_id'], $rs_dst, $hierarchy+1);
            }
        }
    }
    //
    // --------------------------------------------------------------------------------
    // 表示用のぱんくず取得
    // --------------------------------------------------------------------------------
    //
    public function fetch_beradcrumb()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    category_id,
    parent_category_id,
    permanent_name,
    caption
FROM
    cs_category
ORDER BY
    order_at,
    category_id
EOT;
        $src = $con->query($sql);
        $dst = array();
        //
        $category_id = $this->get_value('parent_category_id');
        //
        $this->recursion_fetch_beradcrumb($src, $category_id, $dst);
        //
        return array_reverse($dst);
    }
    //
    // --------------------------------------------------------------------------------
    // 表示用のぱんくず取得（再帰）
    // --------------------------------------------------------------------------------
    //
    public function recursion_fetch_beradcrumb($src, $category_id, &$dst)
    {
        foreach($src as $v)
        {
            if ($v['category_id'] == $category_id)
            {
                $dst[] = $v;
                self::recursion_fetch_beradcrumb($src, $v['parent_category_id'], $dst);
            }
        }
    }
    //
    // --------------------------------------------------------------------------------
    // 並び変え
    // --------------------------------------------------------------------------------
    //
    public function sort($rs)
    {
        $con = new db();
        //
        $sql = <<< EOT
UPDATE
    cs_category
SET
    order_at = :order_at
WHERE
    category_id = :category_id
EOT;

        $i = 0;
        foreach($rs as $v)
        {
            $i++;
            $sql_params = array
            (
                ':order_at' => $i,
                ':category_id'  => $v
            );
            $con->query($sql, $sql_params);
        }
    }
}
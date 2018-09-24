<?php
namespace app\model\controller\admin\page;

use \core\db;

class sort extends \core\fieldset
{
    // ################################################################################
    // コンストラクタ
    // ################################################################################
    public function __construct()
    {
        parent::__construct();
        $this->append('page_tree');
    }
    // ################################################################################
    //
    // ################################################################################
    public function update()
    {
        $con = new db();
        //
        $sql_1 = <<< EOT
SELECT
    page_id,
    parent_page_id,
    permanent_name,
    order_at
FROM
    cs_page
EOT;
        $rs_page = $con->query($sql_1);
        //
        $rs_page_tree = json_decode($this->get_value('page_tree'), true);
        self::recursion_update($rs_page_tree, '#');
        //
        foreach($rs_page_tree as $k => $v)
        {
            $key = array_search($v['page_id'], array_column($rs_page, 'page_id'));
            if
            (
                $v['parent_page_id'] != $rs_page[$key]['parent_page_id'] OR
                $v['order_at'] != $rs_page[$key]['order_at']
            )
            {
                $sql_2 = <<< EOT
UPDATE
    cs_page
SET
    parent_page_id = :parent_page_id,
    order_at = :order_at
WHERE
    page_id = :page_id;
EOT;
                $sql_params_2 = array
                (
                    'parent_page_id' => $v['parent_page_id'],
                    'order_at' => $v['order_at'],
                    'page_id' => $v['page_id'],
                );
                $con->query($sql_2, $sql_params_2);
            }
        }
    }
    // ################################################################################
    //
    // ################################################################################
    private static function recursion_update(&$src, $parent)
    {
        $order = 0;
        foreach($src as $k => $v)
        {
            if ($v['parent'] == $parent)
            {
                $order += 1;
                $src[$k]['order_at'] = $order;
                $src[$k]['page_id'] = $v['id'];
                if ($v['parent'] == '#')
                {
                    $src[$k]['parent_page_id'] = 0;
                }
                else
                {
                    $src[$k]['parent_page_id'] = $v['parent'];
                }
                self::recursion_update($src, $v['id']);
            }
        }
    }

}
<?php
namespace app\cscms\model\helper;

use \core\db;
use \core\route;

class page
{
    // --------------------------------------------------------------------------------
    // 表示用ぱんくず取得
    // --------------------------------------------------------------------------------
    public static function fetch_beradcrumb($parent_page_id)
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    page_id,
    parent_page_id,
    page_title,
    permanent_name
FROM
    cs_page
ORDER BY
    order_at,
    page_id
EOT;
        $src = $con->query($sql);
        $dst = array();
        //
        self::recursion_fetch_beradcrumb($src, $parent_page_id, $dst);
        //
        return array_reverse($dst);
    }
    // ----------
    // 表示用ぱんくず取得（再帰）
    // ----------
    private static function recursion_fetch_beradcrumb($src, $page_id, &$dst) 
    {
        foreach($src as $v)
        {
            if ($v['page_id'] == $page_id)
            {
                $dst[] = $v;
                self::recursion_fetch_beradcrumb($src, $v['parent_page_id'], $dst);
            }
        }
    }
    // --------------------------------------------------------------------------------
    // 表示用ツリー構造データの取得
    // --------------------------------------------------------------------------------
    public static function fetch_tree()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    page_id,
    parent_page_id,
    page_title
FROM
        cs_page
ORDER BY
    order_at,
    page_id
EOT;
        $rs_src = $con->query($sql);
        //
        $rs_dst = array();
        self::recursion_fetch_tree($rs_src, 0, $rs_dst, 1);
        //
        return $rs_dst;
    }
    // ----------
    // 表示用ツリー構造データの取得（再帰）
    // ----------
    private static function recursion_fetch_tree($rs_src, $parent_page_id, &$rs_dst, $hierarchy=1)
    {
        foreach($rs_src as $src)
        {
            if ($src['parent_page_id'] == $parent_page_id)
            {
                $obj = $src;
                $obj['tree_title'] = '└'.str_repeat('─', $hierarchy-1).' '.$src['page_title'];
                $rs_dst[] = $obj;
                self::recursion_fetch_tree($rs_src, $src['page_id'], $rs_dst, $hierarchy+1);
            }
        }
    }
    // --------------------------------------------------------------------------------
    // 現在表示中の URL の page_id を取得する
    // --------------------------------------------------------------------------------
    public static function get_current_page_id()
    {
        $route = new route();
        $route->query();
        //
        $permanent_path = implode('/', $route->params);
        if ($permanent_path == '')
        {
            return 1;
        }
        //
        return self::get_page_id_from_permanent_name(implode('/', $route->params));
    }
    // --------------------------------------------------------------------------------
    // 表示可能かどうか確認
    // --------------------------------------------------------------------------------
    public static function is_visible($values)
    {
        switch ($values['publish_type'])
        {
            case 0: // 非公開
                return false;
                break;
            case 1: // 公開
                return true;
                break;
            case 2: // 時間指定
                $flg = 0;
                if ($values['publish_start'] != '')
                {
                    $flg = $flg + 1;
                }
                if ($values['publish_end'] != '')
                {
                    $flg = $flg + 2;
                }
                switch($flg)
                {
                    case 0: // エラー
                        return false;
                        break;
                    case 1: // 開始日だけ指定
                        if (strtotime('now') > strtotime($values['publish_start']))
                        {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                        break;
                    case 2: // 終了日のみ指定
                        if (strtotime('now') < strtotime($values['publish_end']))
                        {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                        break;
                    case 3: // 開始日と終了日の両方指定
                        if
                        (
                            (strtotime('now') > strtotime($values['publish_start'])) AND
                            (strtotime('now') < strtotime($values['publish_end']))
                        )
                        {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                        break;
                    default: // あり得ない
                        return false;
                        break;
                }
            default:
                return false;
                break;
        }
    }
    // --------------------------------------------------------------------------------
    // permanent_name を、page_id から取得する
    // --------------------------------------------------------------------------------
    public function get_permanent_name_from_page_id($page_id = 0)
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    page_id,
    parent_page_id,
    permanent_name
FROM
    cs_page;
EOT;
        $src = $con->query($sql);
        $dst = array();
        self::recursion_get_permanent_name_from_page_id($src, $page_id, $dst);
        return implode('/', array_reverse($dst));
    }
    // ----------
    // permanent_name を、page_id から取得する（再帰）
    // ----------
    private function recursion_get_permanent_name_from_page_id($src, $page_id, &$dst)
    {
        foreach($src as $v)
        {
            if ($v['page_id'] == $page_id)
            {
                $dst[] = $v['permanent_name'];
                self::recursion_get_permanent_name_from_page_id($src, $v['parent_page_id'], $dst);
            }
        }
    }
    // --------------------------------------------------------------------------------
    // page_id を、permanent_name から取得する
    // --------------------------------------------------------------------------------
    public static function get_page_id_from_permanent_name($permanent_path)
    {
        if ($permanent_path == '')
        {
            return 1;
        }
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    page_id,
    parent_page_id,
    permanent_name
FROM
    cs_page;
EOT;
        $rs_page = $con->query($sql);
        return self::recursion_get_page_id_from_permanent_name(0, $permanent_path, $rs_page);
    }
    // ----------
    // page_id を、permanent_name から取得する（再帰）
    // ----------
    private static function recursion_get_page_id_from_permanent_name($parent_page_id, $permanent_path, $rs_page)
    {
        $rs1 = explode('/', $permanent_path);
        $rs2 = implode('/', array_slice($rs1, 1));
        $permanent_name = $rs1[0];
        foreach($rs_page as $v)
        {
            if (($v['parent_page_id'] == $parent_page_id) and $v['permanent_name'] == $permanent_name)
            {
                if ($rs2 == '')
                {
                    return $v['page_id'];
                }
                else
                {
                    return self::recursion_get_page_id_from_permanent_name($v['page_id'], $rs2, $rs_page);
                }
            }
        }
        return false;
    }
}
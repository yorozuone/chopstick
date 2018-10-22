<?php
namespace app\cscms\model\controller\admin\page;

use \core\db;

class edit extends \app\cscms\model\controller\admin\page\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 検証（更新）
    // --------------------------------------------------------------------------------
    public function check()
    {
        $this->validate('required',     'parent_page_id');
        $this->validate('required',     'page_type');
        $this->validate('required',     'publish_type');
        $this->validate('required',     'page_title');
        $this->validate('max_length',   'page_title', 256);
        $this->validate('required',     'permanent_name');
        $this->validate('max_length',   'permanent_name', 64);
        $this->validate('alnum',        'permanent_name', array('-', '_'));
        $this->validate('max_length',   'external_link',  256);
        // 外部リンク
        if ($this->get_value('page_type') == 2)
        {
            $this->validate('required', 'external_link');
        }
        // 期間指定
        if ($this->get_value('publish_type') == 3)
        {
            if (($this->get_value('publish_start') == '') AND ($this->get_value('publish_end') == ''))
            {
                $this->set_error('publish_type', '公開設定で、時間指定を選択した場合は、開始日時、終了日時のどちらかは必ず入力してください。');
            }
        }
        // 開始日時
        if ($this->get_value('publish_start') != '')
        {
            $this->validate('datetime', 'publish_start');
        }
        // 終了日時
        if ($this->get_value('publish_end') != '')
        {
            $this->validate('datetime', 'publish_end');
        }
        // 親ページ検証
        if ($this->is_edit_permanent_name() == false)
        {
            $this->set_error('parent_page_id', 'ページの階層構造がループしています。親ページを見直してください。');
        }
        // パーマネント名検証
        if ($this->is_edit_permanent_name() == false)
        {
            $this->set_error('permanent_name', 'パーマネント名は、ほかのページで使われています。');
        }
        return $this->is_valid;
    }
    // --------------------------------------------------------------------------------
    // 編集時にパーマネント名が、他のページで使われていないか検証
    // --------------------------------------------------------------------------------
    public function is_edit_permanent_name()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    COUNT(*) AS CNT
FROM
    cs_page
WHERE
    parent_page_id = :parent_page_id AND
    page_id <> :page_id AND
    permanent_name = :permanent_name
EOT;
        $sql_params = array
        (
            ':parent_page_id'   => $this->get_value('parent_page_id'),
            ':page_id'          => $this->get_value('page_id'),
            ':permanent_name'   => $this->get_value('permanent_name'),
        );
        $rs1 = $con->query($sql, $sql_params);
        //
        return ($rs1[0]['CNT'] == 0) ? true : false;
    }
    // --------------------------------------------------------------------------------
    // 指定した親ページからのツリー構造がループしていないか検査
    // --------------------------------------------------------------------------------
    public function is_edit_permanent_page_id()
    {
        $page_id        = $this->get_value('page_id');
        $parent_page_id = $this->get_value('parent_page_id');

        $con = new db();
        //
        $sql_1 = <<< EOT
SELECT
        page_id,
        parent_page_id
FROM
        cs_page
EOT;
        $rs_src = $con->query($sql_1);
        //
        $rs_key = array_search($page_id, array_column($rs_src, 'page_id'));
        $rs_src[$rs_key]['parent_page_id'] = $parent_page_id;
        //
        $rs_dst = array();
        //
        $is_loop = false;
        //
        $this->recursion_is_edit_permanent_page_id($rs_src, $parent_page_id, $rs_dst, $is_loop, $cnt);
        //
        return !$is_loop;
    }
    // ----------
    // 指定した親ページからのツリー構造がループしていないか検査（再帰）
    // ----------
    private function recursion_is_edit_permanent_page_id($rs_src, $parent_page_id, &$rs_dst, &$is_loop, &$cnt)
    {
        foreach($rs_src as $src)
        {
            if ($is_loop == true)
            {
                return;
            }
            if ($src['parent_page_id'] == $parent_page_id)
            {
                if (!isset($rs_dst[$src['page_id']]))
                {
                    $rs_dst[$src['page_id']] = 0;
                }
                $rs_dst[$src['page_id']] += 1;
                if ($rs_dst[$src['page_id']] > 1)
                {
                    $is_loop = true;
                }
                $this->recursion_check_parent_page_id($rs_src, $src['page_id'], $rs_dst, $is_loop, $cnt);
            }
        }
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
        $sql_1 = <<< EOT
UPDATE
    cs_page
SET
    parent_page_id = :parent_page_id,
    page_status = 1,
    page_type = :page_type,
    page_title = :page_title,
    head_title = :head_title,
    breadcrumb_caption = :breadcrumb_caption,
    publish_status = :publish_status,
    publish_type = :publish_type,
    publish_start = :publish_start,
    publish_end = :publish_end,
    publish_navi = :publish_navi,
    publish_list = :publish_list,
    permanent_name = :permanent_name,
    external_link = :external_link,
    area_head_top = :area_head_top,
    area_head_bottom = :area_head_bottom,
    area_body_top = :area_body_top,
    area_body_bottom = :area_body_bottom,
    template_key = :template_key,
    updated_at = NOW()
WHERE
    page_id = :page_id;
EOT;
        $sql_params_1 = array
        (
            ':page_id'              => $this->get_value('page_id'),
            ':parent_page_id'       => $this->get_value('parent_page_id'),
            ':page_type'            => $this->get_value('page_type'),
            ':page_title'           => $this->get_value('page_title'),
            ':head_title'           => $this->get_value('head_title'),
            ':breadcrumb_caption'   => $this->get_value('breadcrumb_caption'),
            ':publish_status'       => $this->get_value('publish_status'),
            ':publish_type'         => $this->get_value('publish_type'),
            ':publish_start'        => $this->get_value('publish_start') == ''   ? NULL : $this->get_value('publish_start'),
            ':publish_end'          => $this->get_value('publish_end') == ''     ? NULL : $this->get_value('publish_end'),
            ':publish_navi'         => $this->get_value('publish_navi'),
            ':publish_list'         => $this->get_value('publish_list'),
            ':permanent_name'       => $this->get_value('permanent_name'),
            ':external_link'        => $this->get_value('external_link'),
            ':area_head_top'        => $this->get_value('area_head_top'),
            ':area_head_bottom'     => $this->get_value('area_head_bottom'),
            ':area_body_top'        => $this->get_value('area_body_top'),
            ':area_body_bottom'     => $this->get_value('area_body_bottom'),
            ':template_key'         => $this->get_value('template_key'),
        );
        //
        $con->query($sql_1, $sql_params_1);
        // ------------------------------------------------------------
        // ページ・カテゴリ更新
        // ------------------------------------------------------------
        $sql_4 = <<< EOT
DELETE
FROM
    cs_page_category
WHERE
    page_id = :page_id;
EOT;
        $sql_params_4 = array
        (
            ':page_id' => $this->get_value('page_id'),
        );
        $con->query($sql_4, $sql_params_4);
        //
        $sql_5 = <<< EOT
INSERT INTO cs_page_category
(
    page_id,
    category_id,
    created_at,
    updated_at
)
VALUES
(
    :page_id,
    :category_id,
    NOW(),
    NOW()
);
EOT;
        if (is_array($this->get_value('category_ids')))
        {
            foreach($this->get_value('category_ids') as $v)
            {
                $sql_params_5 = array
                (
                    ':page_id' => $this->get_value('page_id'),
                    ':category_id' => $v,
                );
                $con->query($sql_5, $sql_params_5);
            }
        }
        // ------------------------------------------------------------
        // ページ・タグ更新
        // ------------------------------------------------------------
        $sql_6 = <<< EOT
DELETE
FROM
    cs_page_tag
WHERE
    page_id = :page_id;
EOT;
        $sql_params_6 = array
        (
            ':page_id' => $this->get_value('page_id'),
        );
        $con->query($sql_6, $sql_params_6);
        //
        $sql_7 = <<< EOT
INSERT INTO cs_page_tag
(
    page_id,
    tag_id,
    created_at,
    updated_at
)
VALUES
(
    :page_id,
    :tag_id,
    NOW(),
    NOW()
);
EOT;
        foreach(explode(' ', $this->get_value('tags')) as $v)
        {
            $tag_id = $this->get_tag_id($v);
            $sql_params_7 = array
            (
                ':page_id' => $this->get_value('page_id'),
                ':tag_id' => $tag_id,
            );
             $con->query($sql_7, $sql_params_7);
        }
        //
        return true;
    }
}
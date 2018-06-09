<?php
namespace app\model\controller\admin\page;

use \core\db;

class create extends \app\model\controller\admin\page\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 検証（作成）
    // --------------------------------------------------------------------------------
    public function check()
    {
        $this->validate('required',     'parent_page_id');
        $this->validate('required',     'page_type');
        $this->validate('required',     'publish_type');
        $this->validate('required',     'page_title');
        $this->validate('max_length',   'page_title', 256);
        $this->validate('required',     'head_title');
        $this->validate('max_length',   'head_title', 256);
        $this->validate('required',     'breadcrumb_caption');
        $this->validate('max_length',   'breadcrumb_caption', 256);
        $this->validate('required',     'permanent_name');
        $this->validate('max_length',   'permanent_name', 64);
        $this->validate('alnum',        'permanent_name', array('-', '_'));
        $this->validate('max_length',   'external_link', 256);
        // 外部リンク
        if ($this->get_value('page_type') == 2)  {
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
        // パーマネント名検証
        if ($this->is_create_permanent_name() == false)
        {
            $this->set_error('permanent_name', 'パーマネント名は、ほかのページで使われています。');
        }
        return $this->is_valid;
    }
    // --------------------------------------------------------------------------------
    // 作成時にパーマネント名が、他のページで使われていないか検証
    // --------------------------------------------------------------------------------
    public function is_create_permanent_name()
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
    permanent_name = :permanent_name
EOT;
        $sql_params = array
        (
            ':parent_page_id' => $this->get_value('parent_page_id'),
            ':permanent_name' => $this->get_value('permanent_name'),
        );
        //
        $rs = $con->query($sql, $sql_params);
        //
        return (($rs[0]['CNT'] == 0) ? true : false);
    }
    // ################################################################################
    // データ操作
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 作成
    // --------------------------------------------------------------------------------
    public function create()
    {
        $con = new db();
        // ------------------------------------------------------------
        // 並び順最新取得
        // ------------------------------------------------------------
        $sql_1 = <<< EOT
SELECT
    MAX(order_at) as order_at
FROM
    cs_page
WHERE
    parent_page_id = :parent_page_id
EOT;
        $sql_params_1 = array
        (
            ':parent_page_id' => $this->get_value('parent_page_id'),
        );
        $rs_1 = $con->query($sql_1, $sql_params_1);
        //
        $this->set_value('order_at', $rs_1[0]['order_at'] + 1);
        // ------------------------------------------------------------
        // ページ追加
        // ------------------------------------------------------------
        $sql_2 = <<< EOT
INSERT INTO cs_page
(
    parent_page_id,
    composer_key,
    page_type,
    page_title,
    head_title,
    breadcrumb_caption,
    publish_status,
    publish_type,
    publish_start,
    publish_end,
    publish_navi,
    publish_list,
    permanent_name,
    external_link,
    area_head,
    area_body_tail,
    template_key,
    order_at,
    created_at,
    updated_at
)
VALUE
(
    :parent_page_id,
    :composer_key,
    :page_type,
    :page_title,
    :head_title,
    :breadcrumb_caption,
    :publish_status,
    :publish_type,
    :publish_start,
    :publish_end,
    :publish_navi,
    :publish_list,
    :permanent_name,
    :external_link,
    :area_head,
    :area_body_tail,
    :template_key,
    :order_at,
    NOW(),
    NOW()
);
EOT;
        $sql_params_2 = array
        (
            ':parent_page_id'       => $this->get_value('parent_page_id'),
            ':composer_key'         => $this->get_value('composer_key'),
            ':page_type'            => $this->get_value('page_type'),
            ':page_title'           => $this->get_value('page_title'),
            ':head_title'           => $this->get_value('head_title'),
            ':breadcrumb_caption'   => $this->get_value('breadcrumb_caption'),
            ':publish_status'       => $this->get_value('publish_status'),
            ':publish_type'         => $this->get_value('publish_type'),
            ':publish_start'        => $this->get_value('publish_start') == '' ? NULL : $this->get_value('publish_start'),
            ':publish_end'          => $this->get_value('publish_end')   == '' ? NULL : $this->get_value('publish_end'),
            ':publish_navi'         => $this->get_value('publish_navi'),
            ':publish_list'         => $this->get_value('publish_list'),
            ':permanent_name'       => $this->get_value('permanent_name'),
            ':external_link'        => $this->get_value('external_link'),
            ':area_head'            => $this->get_value('area_head'),
            ':area_body_tail'       => $this->get_value('area_body_tail'),
            ':template_key'         => $this->get_value('template_key'),
            ':order_at'             => $this->get_value('order_at'),
        );
        //
        $con->query($sql_2, $sql_params_2);
        // ------------------------------------------------------------
        // ページID取得
        // ------------------------------------------------------------
        $sql_3 = <<< EOT
SELECT
    LAST_INSERT_ID() as page_id;
EOT;
        $rs_3 = $con->query($sql_3);
        $this->set_value('page_id', $rs_3[0]['page_id']);
        // ------------------------------------------------------------
        // ページ・カテゴリ登録
        // ------------------------------------------------------------
        $sql_4 = <<< EOT
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
                $sql_params_4 = array
                (
                    ':page_id' => $this->get_value('page_id'),
                    ':category_id' => $v,
                );
                $con->query($sql_4, $sql_params_4);
            }
        }
        // ------------------------------------------------------------
        // ページ・タグ登録
        // ------------------------------------------------------------
        $sql_5 = <<< EOT
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
            $sql_params_5 = array
            (
                ':page_id' => $this->get_value('page_id'),
                ':tag_id' => $tag_id,
            );
            $con->query($sql_5, $sql_params_5);
        }
        //
        return true;
    }
}
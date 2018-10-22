<?php
namespace app\cscms\model\controller\admin\page;

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
        $this->append('page_id',            'ページId', 1);
        //
        $this->append('parent_page_id',     '親ページ', 0);
        $this->append('page_status',        'ページStatus', 0);
        $this->append('composer_key',       'コンポーザーKey', 'Standard');
        $this->append('page_title',         'ページ・タイトル');
        $this->append('breadcrumb_caption', 'パンくず・見出し');
        $this->append('head_title',         'ヘッド・タイトル');
        $this->append('permanent_name',     'パーマネント名', uniqid());
        $this->append('page_type',          'ページ種類', 1);
        $this->append('external_link',      '外部リンク');
        $this->append('area_head_top',      '<head> 最上部');
        $this->append('area_head_bottom',   '<head> 最下部');
        $this->append('area_body_top',      '<body> 最上部');
        $this->append('area_body_bottom',   '<body> 最下部');
        $this->append('publish_status',     '状態', -2);
        $this->append('publish_type',       '公開設定', 1);
        $this->append('publish_start',      '開始日時');
        $this->append('publish_end',        '終了日時');
        $this->append('publish_navi',       'ナビ表示', 1);
        $this->append('publish_list',       'リスト表示', 1);
        $this->append('template_key',       'テンプレート・キー', 'default');
        $this->append('reserved',           '予約ページ');
        $this->append('order_at',           '並び順');
        //
        $this->append('parent_page_title',  '親ページタイトル');
        $this->append('category_ids',       'カテゴリ');
        $this->append('category_captions',  'カテゴリ名');
        $this->append('tags',               'タグ');
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
        // ------------------------------------------------------------
        // ページのデータを読込
        // ------------------------------------------------------------
        $sql_1 = <<< EOT
SELECT
    cs_page.page_id,
    cs_page.parent_page_id,
    T1.page_title as parent_page_title,
    cs_page.composer_key,
    cs_page.page_title,
    cs_page.head_title,
    cs_page.breadcrumb_caption,
    cs_page.permanent_name,
    cs_page.page_type,
    cs_page.external_link,
    cs_page.area_head_top,
    cs_page.area_head_bottom,
    cs_page.area_body_top,
    cs_page.area_body_bottom,
    cs_page.publish_status,
    cs_page.publish_type,
    cs_page.publish_start,
    cs_page.publish_end,
    cs_page.template_key
FROM
    cs_page
LEFT JOIN cs_page as T1 ON
    cs_page.parent_page_id = T1.page_id
WHERE
    cs_page.page_id = :page_id
EOT;
        $sql_params_1 = array
        (
            ':page_id' => $this->get_value('page_id'),
        );
        $rs_1 = $con->query($sql_1, $sql_params_1);
        //
        if (!isset($rs_1[0]))
        {
            return false;
        }
        $this->set_values($rs_1[0]);
        //
        $sql_2 = <<< EOT
SELECT
    page_id,
    category_id
FROM
    cs_page_category
WHERE
    page_id = :page_id;
EOT;
        $sql_params_2 = array
        (
            ':page_id' => $this->get_value('page_id'),
        );
        $rs_2 = $con->query($sql_2, $sql_params_2);
        //
        $category_ids = array();
        foreach($rs_2 as $v)
        {
            $category_ids[] = $v['category_id'];
        }
        $this->set_value('category_ids', $category_ids);
        //
        $sql_3 = <<< EOT
SELECT
    cs_page_tag.page_id,
    cs_page_tag.tag_id,
    cs_tag.caption
FROM
    cs_page_tag
JOIN
    cs_tag
ON
    cs_page_tag.tag_id = cs_tag.tag_id
WHERE
    cs_page_tag.page_id = :page_id;
EOT;
        $sql_params_3 = array
        (
            ':page_id' => $this->get_value('page_id'),
        );
        $rs_3 = $con->query($sql_3, $sql_params_3);
        //
        $this->set_value('tags', implode(' ', array_column($rs_3, 'caption')));
        //
        return true;
    }
    // --------------------------------------------------------------------------------
    // tag_id を取得（存在しなかった場合は、作成）
    // --------------------------------------------------------------------------------
    public function get_tag_id($caption)
    {
        $con = new db();
        //
        $sql_1 = <<< EOT
SELECT
    tag_id
FROM
    cs_tag
WHERE
    caption = :caption;
EOT;
        $sql_params_1 = array
        (
            ':caption' => $caption,
        );
        $rs_1 = $con->query($sql_1, $sql_params_1);
        //
        if (isset($rs_1[0]))
        {
            return $rs_1[0]['tag_id'];
        }
        //
        $sql_2 = <<< EOT
INSERT INTO cs_tag
(
    caption,
    created_at,
    updated_at
)
VALUES
(
    :caption,
    NOW(),
    NOW()
)
EOT;
        $sql_params_2 = array
        (
            ':caption' => $caption,
        );
        $rs_2 = $con->query($sql_2, $sql_params_2);
        //
        $sql_3 = <<< EOT
SELECT
    LAST_INSERT_ID() as tag_id;
EOT;
        $rs_3 = $con->query($sql_3);
        //
        return $rs_3[0]['tag_id'];
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function fetch_composer_block()
    {
        $con = new db();
        //
        $sql_1 = <<< EOT
SELECT
    composer_key,
    composer_block_key,
    block_key,
    caption,
    description,
    order_at,
    created_at,
    updated_at
FROM
    cs_composer_block
WHERE
    composer_key = :composer_key
ORDER BY
    order_at
EOT;
        $sql_params_1 = array
        (
            ':composer_key' => $this->get_value('composer_key'),
        );
        $rs_1 =  $con->query($sql_1, $sql_params_1);
        //
        $rs_2 = array();
        //
        foreach($rs_1 as $v)
        {
            $block = '\\app\\cscms\\block\\'.$v['block_key'].'\\controller';
            $block_obj = new $block($this->get_value('page_id'), $v['composer_block_key']);
            $rs_2[] = $block_obj;
        }
        return $rs_2;
    }
}
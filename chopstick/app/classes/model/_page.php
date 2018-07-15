<?php
namespace app\model;

use \core\db;

class page extends \core\fieldset
{
    public function __construct()
    {
        parent::__construct();
        //
        $this->append('page_id',            'ページId', 1);
        //
        $this->append('parent_page_id',     '親ページ', 0);
        $this->append('composer_key',       'コンポーザーKey', 'Standard');
        $this->append('page_title',         'ページ・タイトル');
        $this->append('breadcrumb_caption', 'パンくず・見出し');
        $this->append('head_title',         'ヘッド・タイトル');
        $this->append('permanent_name',     'パーマネント名');
        $this->append('page_type',          'ページ種類', 1);
        $this->append('external_link',      '外部リンク');
        $this->append('area_head',          'area_head');
        $this->append('area_body_tail',     'area_body_tail');
        $this->append('publish_status',     '状態', -2);
        $this->append('publish_type',       '公開設定', 1);
        $this->append('publish_start',      '開始日時');
        $this->append('publish_end',        '終了日時');
        $this->append('publish_navi',       'ナビ表示', 1);
        $this->append('publish_list',       'リスト表示', 1);
        $this->append('template_key',       'テンプレート・キー');
        $this->append('reserved',           '予約ページ');
        $this->append('order_at',           '並び順');
        //
        $this->append('sub_composer',       '', array());
        $this->append('sub_composer_block', '', array());
        $this->append('sub_category',       '', array());
        $this->append('sub_tag',            '', array());
    }
    //
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    //
    public function read()
    {
        $con = new db();
        //
        // ------------------------------------------------------------
        // ページのデータを読込
        // ------------------------------------------------------------
        //
        $sql_1 = <<< EOT
SELECT
    cs_page.page_id,
    cs_page.parent_page_id,
    cs_page.composer_key,
    cs_page.page_title,
    cs_page.head_title,
    cs_page.breadcrumb_caption,
    cs_page.permanent_name,
    cs_page.page_type,
    cs_page.external_link,
    cs_page.area_head,
    cs_page.area_body_tail,
    cs_page.publish_status,
    cs_page.publish_type,
    cs_page.publish_start,
    cs_page.publish_end,
    cs_page.template_key
FROM
    cs_page
WHERE
    cs_page.page_id = :page_id
EOT;
        $sql_params_1 = array
        (
            ':page_id' => $this->get_value('page_id'),
        );
        $rs_1 = $con->query($sql_1, $sql_params_1);
        //
        if (!isset($rs[0]))
        {
            return false;
        }
        $this->set_values($rs[0]);
        //
        return true;
    }
}

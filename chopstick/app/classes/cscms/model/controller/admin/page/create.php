<?php
namespace app\cscms\model\controller\admin\page;

use \core\db;

class create extends \app\cscms\model\controller\admin\page\base
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
        $this->validate('required',     'composer_key');
        return $this->is_valid;
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
    page_status,
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
    area_head_top,
    area_head_bottom,
    area_body_top,
    area_body_bottom,
    template_key,
    order_at,
    created_at,
    updated_at

)
VALUE
(
    :parent_page_id,
    0,
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
    :area_head_top,
    :area_head_bottom,
    :area_body_top,
    :area_body_bottom,
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
            ':area_head_top'        => $this->get_value('area_head_top'),
            ':area_head_bottom'     => $this->get_value('area_head_bottom'),
            ':area_body_top'        => $this->get_value('area_body_top'),
            ':area_body_bottom'     => $this->get_value('area_body_bottom'),
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
    }
}
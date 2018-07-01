<?php
namespace app\model\controller\admin\media;

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
        $this->append('media_id',               'メディアId');
        //
        $this->append('mediafolder_id',         'メディアフォルダーId');
        $this->append('page_id',                'ページId');
        $this->append('stack_key',              'スタック・キー');
        $this->append('file_name',              'ファイル名');
        $this->append('file_name_org',          '元ファイル名');
        $this->append('description',            '説明');
        $this->append('mime_type',              'mime_type');
        //
        $this->append('mediafolder_caption',    'メディアフォルダー見出し');
        $this->append('guide_message',          'ガイド・メッセージ');
    }
    // ################################################################################
    // 検証
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 検証（作成）
    // --------------------------------------------------------------------------------
    public function check()
    {
        return $this->is_valid;
    }
    // ################################################################################
    // データ操作
    // ################################################################################
    // ------------------------------------------------------------
    // 読込
    // ------------------------------------------------------------
    public function read()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    cs_media.media_id,
    cs_media.mediafolder_id,
    cs_mediafolder.caption AS mediafolder_caption,
    cs_media.file_name,
    cs_media.mime_type,
    cs_media.description
FROM
    cs_media
LEFT JOIN
    cs_mediafolder
ON
    cs_media.mediafolder_id = cs_mediafolder.mediafolder_id
WHERE
    cs_media.media_id = :media_id;
EOT;
        $sql_params = array
        (
            ':media_id' => $this->get_value('media_id'),
        );
        $rs = $con->query($sql, $sql_params);
        //
        if (isset($rs[0]) == false)
        {
            return false;
        }
        $this->set_values($rs[0]);
        //
        return true;
    }
}

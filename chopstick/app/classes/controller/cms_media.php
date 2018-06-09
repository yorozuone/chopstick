<?php
namespace app\controller;

use \core\db;

class cms_media extends \app\controller
{
    private $media_id   = '';
    private $file_name  = '';
    private $mime_type  = '';
    //
    // --------------------------------------------------------------------------------
    // 表示用のフォルダ名取得
    // --------------------------------------------------------------------------------
    //
    public function before()
    {
        parent::before();
    }
    //
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    //
    function action_index($params)
    {
        $this->query($params);
        //
        header('Content-Type: '.$this->mime_type);
        readfile(CS_BASE_DIR.'media/'.$this->file_name);
    }
    //
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    //
    function action_thumbnail($params)
    {
        $this->query($params);
        //
        header('Content-Type: '.$this->mime_type);
        readfile(CS_BASE_DIR.'media/thumbnail/'.$this->file_name);
    }
    //
    // --------------------------------------------------------------------------------
    // 変数設定
    // --------------------------------------------------------------------------------
    //
    private function query($params)
    {
        $this->media_id = isset($params[0]) ? $params[0] : '';
        //
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    mime_type,
    file_name
FROM
    cs_media
WHERE
    media_id = :media_id;
EOT;
        $sql_params = array
        (
            ':media_id' => $this->media_id,
        );
        $rs = $con->query($sql, $sql_params);
        //
        if (isset($rs[0]))
        {
            $this->file_name = $rs[0]['file_name'];
            $this->mime_type = $rs[0]['mime_type'];
        }
    }
}
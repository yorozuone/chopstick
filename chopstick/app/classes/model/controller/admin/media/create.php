<?php
namespace app\model\controller\admin\media;

use \core\db;

class create extends \app\model\controller\admin\media\base
{
    //
    // ################################################################################
    //
    // 検証
    //
    // ################################################################################
    //
    // --------------------------------------------------------------------------------
    // 検証（作成）
    // --------------------------------------------------------------------------------
    //
    public function check()
    {
        return $this->is_valid;
    }
    //
    // ################################################################################
    //
    // データ操作
    //
    // ################################################################################
    //
    // ------------------------------------------------------------
    // アップロード
    // ------------------------------------------------------------
    //
    public function create()
    {
        if (!isset($_FILES['cs_media']))
        {
            $this->set_value('error_message', '[NG-A] アップロードに失敗しました。');
            return false;
        }
        //
        $cs_org_name = $_FILES['cs_media']['name'];
        $cs_new_name = uniqid().'.'.pathinfo($cs_org_name, PATHINFO_EXTENSION);
        //
        $cs_tmp_path = $_FILES['cs_media']['tmp_name'];
        $cs_new_path = CS_BASE_DIR.'media/'.$cs_new_name;
        $cs_thm_path = CS_BASE_DIR.'media/thumbnail/'.$cs_new_name;
        //
        if (!is_uploaded_file($cs_tmp_path))
        {
            $this->set_value('error_message', '[NG-B] アップロードに失敗しました。');
            return false;
        }
        if (file_exists($cs_new_path))
        {
            $this->set_value('error_message', '[NG-C] アップロードに失敗しました。同一のファイル名のファイルが登録済です。');
            return false;
        }
        if (!move_uploaded_file($cs_tmp_path, $cs_new_path))
        {
            $this->set_value('error_message', '[NG-D] アップロードに失敗しました。');
            return false;
        }
        //
        $cs_mime_type = mime_content_type($cs_new_path);
        //
        $con = new db();
        //
        $sql = <<< EOT
INSERT INTO cs_media
(
    mediafolder_id,
    page_id,
    stack_key,
    file_name_org,
    file_name,
    mime_type,
    created_at,
    updated_at
)
VALUES
(
    :mediafolder_id,
    :page_id,
    :stack_key,
    :file_name_org,
    :file_name,
    :mime_type,
    NOW(),
    NOW()
)
EOT;
        $sql_params = array
        (
            ':mediafolder_id'   => $this->get_value('mediafolder_id'),
            ':page_id'          => $this->get_value('page_id'),
            ':stack_key'        => $this->get_value('stack_key'),
            ':file_name_org'    => pathinfo($cs_org_name, PATHINFO_BASENAME),
            ':file_name'        => pathinfo($cs_new_path, PATHINFO_BASENAME),
            ':mime_type'        => $cs_mime_type,
        );
        $con->query($sql, $sql_params);
        //
        // 120 x 64 サムネイル
        //
        switch($cs_mime_type)
        {
            case 'image/jpeg':
                $dst_x = 120;
                $dst_y = 64;
                //
                $im_src = imagecreatefromjpeg($cs_new_path);
                $im_dst = imagecreatetruecolor($dst_x, $dst_y);
                //
                $im_src_x = imagesx($im_src);
                $im_src_y = imagesy($im_src);
                //
                $im_dst_x = $dst_x;
                $im_dst_y = $dst_x / $im_src_x * $im_src_y;
                //
                if ($im_dst_y > $dst_y)
                {
                    $im_dst_x = $dst_y / $im_src_y * $im_src_x;
                    $im_dst_y = $dst_y;
                }
                $cs_white = imagecolorallocate($im_dst, 0, 0, 0);
                imagefilledrectangle($im_dst, 0, 0, $dst_x, $dst_y, $cs_white);
                imagecopyresampled($im_dst, $im_src, ($dst_x - $im_dst_x) / 2, ($dst_y - $im_dst_y) / 2, 0, 0, $im_dst_x, $im_dst_y, $im_src_x, $im_src_y);
                //
                imagejpeg($im_dst, $cs_thm_path);
                //
                break;
        }
        //
        $this->set_value('error_message', 'アップロードに成功しました。[' . $cs_org_name . ']');
        //
        return true;
    }
}

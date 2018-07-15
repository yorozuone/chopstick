<?php
namespace app\model\dataset\media;

use \core\db;

class create extends \app\model\dataset\media\base
{
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
    // アップロード
    // ------------------------------------------------------------
    public function update()
    {
        if (!isset($_FILES['cs_media']))
        {
            $this->set_value('guide_message', '[NG-A] アップロードに失敗しました。');
            return false;
        }
        $cs_uniqid = uniqid();
        //
        $cs_img_name = $_FILES['cs_media']['name'];
        $cs_tmp_name = $_FILES['cs_media']['tmp_name'];
        //
        $cs_img_folder = CS_BASE_DIR.'media/'.$cs_uniqid;
        $cs_thm_folder = CS_BASE_DIR.'media/'.$cs_uniqid.'/thumbnail';
        //
        $cs_tmp_path = $_FILES['cs_media']['tmp_name'];
        $cs_img_path = $cs_img_folder.'/'.$cs_img_name;
        $cs_thm_path = $cs_thm_folder.'/'.$cs_img_name;
        //
        mkdir($cs_img_folder);
        mkdir($cs_thm_folder);
        //
        if (!is_uploaded_file($cs_tmp_name))
        {
            $this->set_value('guide_message', '[NG-B] アップロードに失敗しました。');
            return false;
        }
        if (file_exists($cs_img_path))
        {
            $this->set_value('guide_message', '[NG-C] アップロードに失敗しました。同一のファイル名のファイルが登録済です。');
            return false;
        }
        if (!move_uploaded_file($cs_tmp_path, $cs_img_path))
        {
            $this->set_value('guide_message', '[NG-D] アップロードに失敗しました。');
            return false;
        }
        $cs_mime_type = mime_content_type($cs_img_path);
        //
        $con = new db();
        //
        $sql = <<< EOT
INSERT INTO cs_media
(
    mediafolder_id,
    page_id,
    stack_key,
    folder_name,
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
    :folder_name,
    :file_name,
    :mime_type,
    NOW(),
    NOW()
)
EOT;
        $sql_params = array
        (
            ':mediafolder_id'   => $this->get_value('mediafolder_id')   == '' ? NULL : $this->get_value('mediafolder_id'),
            ':page_id'          => $this->get_value('page_id')          == '' ? NULL : $this->get_value('page_id'),
            ':stack_key'        => $this->get_value('stack_key')        == '' ? NULL : $this->get_value('stack_key'),
            ':folder_name'      => $cs_uniqid,
            ':file_name'        => $cs_img_name,
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
                $im_src = imagecreatefromjpeg($cs_img_path);
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
        $this->set_value('guide_message', 'アップロードに成功しました。[' . $cs_img_name . ']');
        //
        return true;
    }
}

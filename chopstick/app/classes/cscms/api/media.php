<?php
namespace app\cscms\controller\api;

use \core\response;
use \core\url;

class media extends \app\controller_admin
{
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    public function action_fetch_all_page($params)
    {
        $page_id = isset($params[0]) ? $params[0] : '';
        echo json_encode(\app\cscms\model\recordset\media::fetch_all_page($page_id));
    }
    // --------------------------------------------------------------------------------
    // アップロード
    // --------------------------------------------------------------------------------
    public function action_upload_mediafolder($params)
    {
        $dset_media = new \app\cscms\model\dataset\media\create();
        $mediafolder_id = isset($params[0]) ? $params[0] : '';
        //
        $dset_media->set_value('mediafolder_id', $mediafolder_id);
        $ret = $dset_media->update();
        echo $dset_media->get_value('guide_message');
        return $ret;
    }
    // --------------------------------------------------------------------------------
    // アップロード
    // --------------------------------------------------------------------------------
    public function action_upload_page($params)
    {
        $dset_media = new \app\cscms\model\dataset\media\create();
        $page_id = isset($params[0]) ? $params[0] : '';
        //
        $dset_media->set_value('page_id', $page_id);
        $ret = $dset_media->update();
        echo $dset_media->get_value('guide_message');
        return $ret;
    }
    // --------------------------------------------------------------------------------
    // アップロード
    // --------------------------------------------------------------------------------
    public function action_upload_stack($params)
    {
        $dset_media = new \app\cscms\model\dataset\media\create();
        $stack_key = isset($params[0]) ? $params[0] : '';
        //
        $dset_media->set_value('stack_key', $stack_key);
        $ret = $dset_media->update();
        echo $dset_media->get_value('guide_message');
        return $ret;
    }
    // --------------------------------------------------------------------------------
    // 削除
    // --------------------------------------------------------------------------------
    public function action_delete($params)
    {
        $dset_media = new \app\cscms\model\dataset\media\delete();
        $media_id = isset($params[0]) ? $params[0] : '';
        //
        $dset_media->set_value('media_id', $media_id);
        $ret = $dset_media->update();
    }

 }
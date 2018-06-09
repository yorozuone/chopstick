<?php
namespace app\controller\admin\media;

use \core\response;
use \core\url;

// dataset
use \app\model\controller\admin\media\create as dset_media;

class create extends \app\controller_auth
{
    private $dset_media;
    private $mediafolder_id;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        $this->dset_media = new dset_media();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->mediafolder_id = isset($params[0]) ? $params[0] : '';
        //
        $this->dset_media->set_value('mediafolder_id', $this->mediafolder_id);
        //
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // アップロード
    // --------------------------------------------------------------------------------
    public function action_upload($params)
    {
        $this->mediafolder_id = isset($params[0]) ? $params[0] : '';
        //
        $this->dset_media->set_value('mediafolder_id', $this->mediafolder_id);
        $ret = $this->dset_media->create();
        //
        echo $this->dset_media->get_value('error_message');
        //
        return $ret;
    }
    // ********************************************************************************
    // **** 表示
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    private function display()
    {
        $vars = array
        (
            'mediafolder_id'        => $this->mediafolder_id,
            'mediafolder_caption'   => \app\model\helper\mediafolder::get_caption($this->mediafolder_id),
            'dset_media_values'     => $this->dset_media->get_values(),
        );        
        echo $this->render('controller/admin/media/create/edit.twig', $vars);
    }
}
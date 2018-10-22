<?php
namespace app\cscms\controller\admin\media;

use \core\db;
use \core\fieldset;
use \core\response;
use \core\url;

// dset_media
use \app\cscms\model\controller\admin\media\summary as rs_media;

class summary extends \app\controller_admin
{
    private $mediafolder_id;
    private $rs_media;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        $this->rs_media = new rs_media();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->mediafolder_id = isset($params[0]) ? $params[0] : -1;
        $this->rs_media->set_value('mediafolder_id', $this->mediafolder_id);
        $this->display();
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
            'mediafolder_caption'   => \app\cscms\model\helper\mediafolder::get_caption($this->mediafolder_id),
            'rs_media'            => $this->rs_media->fetch_all(),
        );
        echo $this->render('cscms/controller/admin/media/summary.twig', $vars);
    }
}
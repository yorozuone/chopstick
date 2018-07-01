<?php
namespace app\controller\admin\media;

use \core\db;
use \core\fieldset;
use \core\response;
use \core\url;

// dset_media
use \app\model\controller\admin\media\summary as drec_media;

class summary extends \app\controller_admin
{
    private $mediafolder_id;
    private $drec_media;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        $this->drec_media = new drec_media();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->mediafolder_id = isset($params[0]) ? $params[0] : -1;
        $this->drec_media->set_value('mediafolder_id', $this->mediafolder_id);
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
            'mediafolder_caption'   => \app\model\helper\mediafolder::get_caption($this->mediafolder_id),
            'drec_media'            => $this->drec_media->fetch_all(),
        );
        echo $this->render('controller/admin/media/summary.twig', $vars);
    }
}
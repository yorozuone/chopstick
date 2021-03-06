<?php
namespace app\cscms\controller\admin\media;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

use \app\cscms\model\dataset\media\delete as dset_media;

class delete extends \app\controller_admin
{
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        //
        $this->dset_media = new dset_media();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dset_media->set_value('media_id', isset($params[0]) ? $params[0] : '');
        if ($this->dset_media->read() == false)
        {
            response::redirect(url::create('/cscms/admin/mediafolder/summary'));
        }
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 削除
    // --------------------------------------------------------------------------------
    public function action_update()
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/csroot/admin/auth/login'));
        }
        $this->dset_media->post();
        if ($this->dset_media->read() == false)
        {
            response::redirect(url::create('/cscms/admin/mediafolder/summary'));
        }
        $this->dset_media->update();
        response::redirect(url::create('/cscms/admin/media/summary', array($this->dset_media->get_value('mediafolder_id'))));
    }
    // ********************************************************************************
    // **** 表示
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    function display()
    {
        $vars = array
        (
            'dset_media_values'         => $this->dset_media->get_values(),
            'dset_media_error_messages' => $this->dset_media->get_error_messages(),
        );
        echo $this->render('cscms/controller/admin/media/delete/confirm.twig', $vars);
    }
}
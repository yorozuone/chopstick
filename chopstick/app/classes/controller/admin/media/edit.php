<?php
namespace app\controller\admin\media;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\model\controller\admin\media\edit as dset_media;

class edit extends \app\controller_auth
{
    private $dset_media;
    private $dset_media_validation;
    //
    // ********************************************************************************
    // ****
    // **** アクション
    // ****
    // ********************************************************************************
    //
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    //
    public function before()
    {
        parent::before();
        //
        $this->dset_media = new dset_media();
    }
    //
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    //
    public function action_index($params)
    {
        $this->dset_media->set_value('media_id', isset($params[0]) ? $params[0] : '');
        if ($this->dset_media->read() == false)
        {
            response::redirect(url::create('/admin/mediafolder/media/summary'));
        }
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 更新
    // --------------------------------------------------------------------------------
    public function action_update($params)
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/admin/auth/login'));
        }
        //
        $this->dset_media->post('media_id');
        //
        if ($this->dset_media->read() == false)
        {
            response::redirect(url::create('/admin/mediafolder/media/summary'));
        }
        $this->dset_media->post();
        //
        $this->dset_media->check();
        //
        if ($this->dset_media->is_valid)
        {
            $this->dset_media->update();
            response::redirect(url::create('/admin/media/summary', array($this->dset_media->get_value('mediafolder_id'))));
        }
        else 
        {
            $this->display();
        }
    }
    // ********************************************************************************
    // ****
    // **** 表示
    // ****
    // ********************************************************************************
    //
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    public function display()
    {
        $vars = array
        (
            'dset_media_values'         => $this->dset_media->get_values(),
            'dset_media_error_messages' => $this->dset_media->get_error_messages(),
        );
        echo $this->render('controller/admin/media/edit/edit.twig', $vars);
    }
}
<?php
namespace app\cscms\controller\admin\mediafolder;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

use \app\cscms\model\dataset\mediafolder\delete as dset_folder;

class delete extends \app\controller_admin
{
    private $dset_folder = null;
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
        $this->dset_folder = new dset_folder();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dset_folder->set_value('mediafolder_id', isset($params[0]) ? $params[0] : '');
        if ($this->dset_folder->read() == false)
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
        $this->dset_folder->post();
        //
        if ($this->dset_folder->read() == false)
        {
            response::redirect(url::create('/cscms/admin/mediafolder/summary'));
        }
        $this->dset_folder->delete();
        response::redirect(url::create('/cscms/admin/mediafolder/summary'));
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
            'dset_folder_values'          => $this->dset_folder->get_values(),
            'dset_folder_error_messages'  => $this->dset_folder->get_error_messages(),
        );
        echo $this->render('cscms/controller/admin/mediafolder/delete/confirm.twig', $vars);
    }
}
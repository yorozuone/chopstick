<?php
namespace app\cscms\controller\admin\mediafolder;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\cscms\model\dataset\mediafolder\create as dset_folder;

class create extends \app\controller_admin
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
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 作成
    // --------------------------------------------------------------------------------
    public function action_update($params)
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/csroot/admin/auth/login'));
        }
        $this->dset_folder->post();
        //
        if ($this->dset_folder->check())
        {
            $this->dset_folder->create();
            response::redirect(url::create('/cscms/admin/mediafolder/summary'));
        }
        else 
        {
            $this->display();
        }
    }
    // ********************************************************************************
    // **** 表示
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    public function display()
    {
        $vars = array
        (
            'is_valid'                      => $this->dset_folder->is_valid,
            'dset_folder_values'            => $this->dset_folder->get_values(),
            'dset_folder_error_messages'    => $this->dset_folder->get_error_messages(),
        );
        echo $this->render('cscms/controller/admin/mediafolder/create/edit.twig', $vars);
    }
}
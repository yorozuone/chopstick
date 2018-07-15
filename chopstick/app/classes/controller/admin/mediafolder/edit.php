<?php
namespace app\controller\admin\mediafolder;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\model\dataset\mediafolder\edit as dset_folder;

class edit extends \app\controller_admin
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
        //
        if ($this->dset_folder->read() == false)
        {
            response::redirect(url::create('/admin/mediafolder/summary'));
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
        $this->dset_folder->post();
        //
        if ($this->dset_folder->check())
        {
            $this->dset_folder->update();
            response::redirect(url::create('/admin/mediafolder/summary'));
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
        echo $this->render('controller/admin/mediafolder/edit/edit.twig', $vars);
    }
}
<?php
namespace app\cscms\controller\admin\tag;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

use \app\cscms\model\controller\admin\tag\edit as dset_tag;

class edit extends \app\controller_admin
{
    private $dset_tag = null;
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
        $this->dset_tag = new dset_tag();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dset_tag->set_value('tag_id', isset($params[0]) ? $params[0] : '');
        if ($this->dset_tag->read() == false)
        {
            response::redirect(url::create('/cscms/admin/tag/summary'));
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
            response::redirect(url::create('/csroot/admin/auth/login'));
        }
        $this->dset_tag->post();
        if ($this->dset_tag->check())
        {
            $this->dset_tag->update();
            response::redirect(url::create('/cscms/admin/tag/summary'));
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
            'dset_tag_values'         => $this->dset_tag->get_values(),
            'dset_tag_error_messages' => $this->dset_tag->get_error_messages(),
        );
        echo $this->render('cscms/controller/admin/tag/edit/edit.twig', $vars);
    }
}
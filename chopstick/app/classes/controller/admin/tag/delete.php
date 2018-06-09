<?php
namespace app\controller\admin\tag;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

use \app\model\controller\admin\tag\delete as dset_tag;

class delete extends \app\controller_auth
{
    private $dset_tag = null;
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
        $this->dset_tag = new dset_tag();
    }
    //
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    //
    public function action_index($params)
    {
        $this->dset_tag->set_value('tag_id', isset($params[0]) ? $params[0] : '');
        if ($this->dset_tag->read() == false)
        {
            response::redirect(url::create('/admin/tag/summary'));
        }
        $this->display();
    }
    //
    // --------------------------------------------------------------------------------
    // 削除
    // --------------------------------------------------------------------------------
    //
    public function action_update()
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/admin/auth/login'));
        }
        $this->dset_tag->post();
        //
        if ($this->dset_tag->read() == false)
        {
            response::redirect(url::create('/admin/tag/summary'));
        }
        $this->dset_tag->delete();
        response::redirect(url::create('/admin/tag/summary'));
    }
    //
    // ********************************************************************************
    // ****
    // **** 表示
    // ****
    // ********************************************************************************
    //
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    //
    function display()
    {
        $vars = array
        (
            'dset_tag_values'           => $this->dset_tag->get_values(),
            'dset_tag_error_messages'   => $this->dset_tag->get_error_messages(),
        );
        echo $this->render('controller/admin/tag/delete/confirm.twig', $vars);
    }
}
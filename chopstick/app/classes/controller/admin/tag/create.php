<?php
namespace app\controller\admin\tag;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

use \app\model\controller\admin\tag\create as dset_tag;

class create extends \app\controller_admin
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
            response::redirect(url::create('/admin/auth/login'));
        }
        $this->dset_tag->post();
        //
        if ($this->dset_tag->check())
        {
            $this->dset_tag->create();
            response::redirect(url::create('/admin/tag/summary'));
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
            'is_valid'                  => $this->dset_tag->is_valid,
            'dset_tag_values'           => $this->dset_tag->get_values(),
            'dset_tag_error_messages'   => $this->dset_tag->get_error_messages(),
        );
        echo $this->render('controller/admin/tag/create/edit.twig', $vars);
    }
}
<?php
namespace app\csroot\controller\admin\user;

use \core\db;
use \core\fieldset;
use \core\response;
use \core\url;

use \app\csroot\model\controller\admin\user\summary as rs_user;

class summary extends \app\controller_admin
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
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
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
        $rs_user = new rs_user();
        //
        $vars = array
        (
            'rs_user' => $rs_user->fetch_all(),
        );
        echo $this->render('cscms/controller/admin/user/summary.twig', $vars);
    }
    // --------------------------------------------------------------------------------
    // 表示用のページ一覧取得
    // --------------------------------------------------------------------------------
    public function dset_user()
    {
        $con = new db();
        //
        //
        return $rs;
    }
}
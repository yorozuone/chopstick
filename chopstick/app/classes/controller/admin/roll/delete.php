<?php
namespace app\controller\admin\roll;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\model\controller\admin\roll\delete as dataset_roll;

class delete extends \app\controller_admin
{
    private $dataset_roll;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        $this->dataset_roll = new dataset_roll();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dataset_roll->set_value('roll_key', isset($params[0]) ? $params[0] : '');
        if ($this->dataset_roll->read() == false)
        {
            response::redirect(url::create('/admin/roll'));
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
            response::redirect(url::create('/admin/auth/login'));
        }
        $this->dataset_roll->post();
        if ($this->dataset_roll->read() == false)
        {
            response::redirect(url::create('/admin/roll/summary'));
        }
        $this->dataset_roll->delete();
        response::redirect(url::create('/admin/roll/summary'));
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
            'values' => $this->dataset_roll->get_values(),
        );
        echo $this->render('controller/admin/roll/delete/confirm.twig', $vars);
    }
}
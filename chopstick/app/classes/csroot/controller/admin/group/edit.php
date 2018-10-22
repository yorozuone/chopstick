<?php
namespace app\csroot\controller\admin\group;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\csroot\model\controller\admin\group\edit as dataset_group;
use \app\model\recordset\cs\roll as rs_roll;

class edit extends \app\controller_admin
{
    private $dataset_group;
    private $rs_roll;
    // ********************************************************************************
    // **** アクション
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // コントローラー起動事前処理
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        $this->dataset_group = new dataset_group();
        $this->rs_roll = new rs_roll();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dataset_group->set_value('group_key', isset($params[0]) ? $params[0] : '');
        if ($this->dataset_group->read() == false)
        {
            response::redirect(url::create('/admin/group/summary'));
        }
        $this->display();
    }
    // --------------------------------------------------------------------------------
    // 更新
    // --------------------------------------------------------------------------------
    public function action_update()
    {
        if (!csrf::check())
        {
            auth::logout();
            response::redirect(url::create('/csroot/admin/auth/login'));
        }
        $this->dataset_group->post();
        if ($this->dataset_group->check())
        {
            $this->dataset_group->update();
            response::redirect(url::create('/admin/group/summary'));
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
        $rs_roll = $this->rs_roll->fetch_all();
        $roll_keys = $this->dataset_group->get_value('roll_keys');
        foreach($rs_roll as $k => $v)
        {
            if (array_search($v['roll_key'], $roll_keys) === false)
            {
                $rs_roll[$k]['checked'] = 0;
            }
            else
            {                
                $rs_roll[$k]['checked'] = 1;
            }
        }
        $vars = array
        (
            'is_valid'          => $this->dataset_group->is_valid,
            'values'            => $this->dataset_group->get_values(),
            'error_messages'    => $this->dataset_group->get_error_messages(),
            'rs_roll'           => $rs_roll,
        );
        echo $this->render('cscms/controller/admin/group/edit/edit.twig', $vars);
    }
}
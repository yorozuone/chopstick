<?php
namespace app\controller\admin\group;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\model\controller\admin\group\edit as dset_group;
use \app\model\recordset\roll as rs_roll;

class edit extends \app\controller_admin
{
    private $dset_group;
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
        $this->dset_group = new dset_group();
        $this->rs_roll = new rs_roll();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dset_group->set_value('group_key', isset($params[0]) ? $params[0] : '');
        if ($this->dset_group->read() == false)
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
            response::redirect(url::create('/admin/auth/login'));
        }
        $this->dset_group->post();
        if ($this->dset_group->check())
        {
            $this->dset_group->update();
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
        $roll_keys = $this->dset_group->get_value('roll_keys');
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
            'is_valid'                  => $this->dset_group->is_valid,
            'dset_group_values'         => $this->dset_group->get_values(),
            'dset_group_error_messages' => $this->dset_group->get_error_messages(),
            'rs_roll'                 => $rs_roll,
        );
        echo $this->render('controller/admin/group/edit/edit.twig', $vars);
    }
}
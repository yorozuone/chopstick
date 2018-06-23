<?php
namespace app\controller\admin\group;

use \core\auth;
use \core\csrf;
use \core\response;
use \core\url;

// dataset
use \app\model\controller\admin\group\delete as dset_group;
use \app\model\datasource\roll as drec_roll;

class delete extends \app\controller_admin
{
    private $dset_group;
    private $drec_roll;
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
        $this->drec_roll = new drec_roll();
    }
    // --------------------------------------------------------------------------------
    // 既定
    // --------------------------------------------------------------------------------
    public function action_index($params)
    {
        $this->dset_group->set_value('group_key', isset($params[0]) ? $params[0] : '');
        if ($this->dset_group->read() == false)
        {
            response::redirect(url::create('/admin/group'));
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
        $this->dset_group->post();
        if ($this->dset_group->read() == false)
        {
            response::redirect(url::create('/admin/group/summary'));
        }
        $this->dset_group->delete();
        response::redirect(url::create('/admin/group/summary'));
    }
    // ********************************************************************************
    // **** 表示
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // 表示
    // --------------------------------------------------------------------------------
    function display()
    {
        $drec_roll = $this->drec_roll->fetch_all();
        $roll_keys = $this->dset_group->get_value('roll_keys');
        foreach($drec_roll as $k => $v)
        {
            if (array_search($v['roll_key'], $roll_keys) === false)
            {
                $drec_roll[$k]['checked'] = 0;
            }
            else
            {                
                $drec_roll[$k]['checked'] = 1;
            }
        }
        $vars = array
        (
            'dset_group_values'         => $this->dset_group->get_values(),
            'drec_roll'                 => $drec_roll,
        );
        echo $this->render('controller/admin/group/delete/confirm.twig', $vars);
    }
}
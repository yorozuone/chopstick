<?php
namespace core;

use \core\response;

class route
{
    //
    const UNIQ_KEY              = 'CORE_LIB_ROUTE';
    //
    public $config              = array();
    //
    public $default_route_path  = '';
    public $route_path          = '';
    //
    public $controller_name     = '';
    public $action_name         = '';
    public $params              = array();
    // --------------------------------------------------------------------------------
    // コンストラクタ実行
    // --------------------------------------------------------------------------------
    public function __construct()
    {
        // 設定情報の読込
        $this->config = config::read('route');
    }
    //
    // --------------------------------------------------------------------------------
    // route を取得
    // --------------------------------------------------------------------------------
    //
    public function query()
    {
        // URL を取得
        $this->default_route_path = $this->set_route_path();
        // 既定ページ (root) の指定
        if ($this->route_path == '')
        {
            $this->route_path = $this->config['root'];
        }
        // ルート変更
        $this->change_route_path();
        // URL から controller、action、params を取得
        if($this->explode_route_path())
        {
            return true;
        }
        // home コントローラーを見つける
        $this->route_path = $this->config['root'].'/'.$this->default_route_path;
        if ($this->explode_route_path())
        {
            return true;
        }
        // 404 コントローラーを見つける
        $this->route_path = $this->config['404'].'/'.$this->default_route_path;
        if ($this->explode_route_path())
        {
            return true;
        }
        // controller が見つけられなかった
        die('[core/route/create] controller['.$this->route_path.'] が見つかりません。');
        return false;
    }
    //
    // --------------------------------------------------------------------------------
    // 解析対象となる文字列を URL から抽出
    // --------------------------------------------------------------------------------
    //
    // SCRIPT_NAME : /cscms/index.php
    // REQUEST_URI : /cscms/admin/auth/login/update
    //   ↓
    // admin/auth/login/update
    //
    private function set_route_path()
    {
        $a = substr($_SERVER['SCRIPT_NAME'], 0, -9); //'index.php');
        $b = substr($_SERVER['REQUEST_URI'], strlen($a));
        $c = explode('?', $b);
        //
        $this->route_path = isset($c[0]) ? $c[0] : '';
        return $this->route_path;
    }
    // --------------------------------------------------------------------------------
    // config の replace に従って、route_path を変更します。
    // --------------------------------------------------------------------------------
    public function change_route_path()
    {
        foreach($this->config['replace'] as $k => $v)
        {
            $tmp_route_path = preg_replace($k, $v, $this->route_path);
            if ($this->route_path != $tmp_route_path)
            {
                response::redirect(url::create($tmp_route_path));
            }
        }
    }
    // --------------------------------------------------------------------------------
    // route_path から、controller / action / params を分離します。
    // --------------------------------------------------------------------------------
    private function explode_route_path()
    {
        // クラスを取得
        $route_path = explode('/', $this->route_path);
        // コントローラーを探す
        for($pos=count($route_path); $pos>0;$pos--)
        {
            $tmp_controller_name = '\\app\\controller\\'.implode('\\', array_slice($route_path, 0, $pos));            
            if (class_exists($tmp_controller_name))
            {
                $this->controller_name = $tmp_controller_name;
                break;
            }
        }
        // クラスが取得できなかった場合は失敗を戻す
        if ($this->controller_name == '')
        {
            return false;
        }
        // アクションを取得
        if (isset($route_path[$pos])) {
            $tmp_action_name = 'action_'.$route_path[$pos];
            if (method_exists($this->controller_name, $tmp_action_name))
            {
                $pos++;
                $this->action_name = $tmp_action_name;
            }
        }
        // アクションが取得できなかった場合規定値「action_index」を設定
        if ($this->action_name == '')
        {
            $tmp_action_name = 'action_index';
            if (method_exists($this->controller_name, $tmp_action_name))
            {
                $this->action_name = $tmp_action_name;
            }
        }
        // アクションが取得できなかった場合は失敗を戻す
        if ($tmp_action_name == '')
        {
            return false;
        }
        // パラメーターを取得
        $this->params = array_slice($route_path, $pos);
        //
        return true;
    }
 }
 
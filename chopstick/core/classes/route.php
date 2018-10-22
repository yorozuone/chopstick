<?php
namespace core;

use \core\response;

class route
{
    public $config              = array();
    //
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
        debug::trace('[core/route/__construct] : 開始');
        // 設定情報の読込
        $this->config = config::read('route');
    }
    // --------------------------------------------------------------------------------
    // route を取得
    // --------------------------------------------------------------------------------
    public function query()
    {
        debug::trace('[core/route/query] : 開始');
        // 解析用 URL を取得
        $this->route_path = $this->get_route_path();
        $org_route_path = $this->route_path;
        // 既定ページ (root) の指定
        if ($this->route_path == '')
        {
            $this->route_path = $this->config['root'];
        }
        // config の設定に従って解析用 URL を書き換え
        $this->change_route_path();
        // 解析用 URL から controller、action、params を取得
        if($this->explode_route_path())
        {
            return true;
        }
        // home コントローラーを見つける
        $this->route_path = $this->config['root'].'/'.$org_route_path;
        if ($this->explode_route_path())
        {
            return true;
        }
        // 404 コントローラーを見つける
        $this->route_path = $this->config['404'].'/'.$org_route_path;
        if ($this->explode_route_path())
        {
            return true;
        }
        // controller が見つけられなかった
        die('[core/route/create] controller['.$this->route_path.'] が見つかりません。');
        return false;
    }
    // --------------------------------------------------------------------------------
    // 解析対象となる文字列を URL から抽出
    // --------------------------------------------------------------------------------
    // SCRIPT_NAME : /cscms/index.php
    // REQUEST_URI : /cscms/cs/admin/auth/login/update
    //   ↓
    // cs/admin/auth/login/update
    //
    private function get_route_path()
    {
        debug::trace('[core/route/get_route_path] : 開始');
        $a = substr($_SERVER['SCRIPT_NAME'], 0, -9); //'index.php');
        $b = substr($_SERVER['REQUEST_URI'], strlen($a));
        $c = explode('?', $b);
        //
        return isset($c[0]) ? $c[0] : '';
    }
    // --------------------------------------------------------------------------------
    // config の replace に従って、route_path を変更します。
    // --------------------------------------------------------------------------------
    public function change_route_path()
    {
        debug::trace('[core/route/change_route_path] : 開始');
        foreach($this->config['replace'] as $k => $v)
        {
            $new_route_path = preg_replace($k, $v, $this->route_path);
            if ($this->route_path != $new_route_path)
            {
                response::redirect(url::create($new_route_path));
            }
        }
    }
    // --------------------------------------------------------------------------------
    // route_path から、controller / action / params を分離します。
    // --------------------------------------------------------------------------------
    private function explode_route_path()
    {
        debug::trace('[core/route/explode_route_path] : 開始');
        // 解析用 URL を分解
        $route_path = explode('/', $this->route_path);
        debug::trace('[core/route/explode_route_path] URL解析を開始 : '.$this->route_path);
        // コントローラーを探す
        for($route_path_pos=count($route_path)-1; $route_path_pos > 0; $route_path_pos--)
        {
            debug::trace('[core/route/explode_route_path] : '.$route_path_pos.' -> '.$route_path[$route_path_pos]);
            $tmp_controller_name = '\\app\\'.$route_path[0].'\\controller\\'.implode('\\', array_slice($route_path, 1, $route_path_pos));
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
        $route_path_pos += 1;
        if (isset($route_path[$route_path_pos])) {
            $tmp_action_name = 'action_'.$route_path[$route_path_pos];
            if (method_exists($this->controller_name, $tmp_action_name))
            {
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
        $this->params = array_slice($route_path, $route_path_pos);
        //
        debug::trace('[core/route/explode_route_path] : URL解析成功');
        debug::trace('[core/route/explode_route_path] : route_path : '.$this->route_path);
        debug::trace('[core/route/explode_route_path] : controller_name : '.$this->controller_name);
        debug::trace('[core/route/explode_route_path] : action_name : '.$this->action_name);
        for($i=0;$i<count($this->params);$i++)
        {
            debug::trace('[core/route/explode_route_path] : params : '.$i.' - '.$this->params[$i]);
        }
        return true;
    }
 }
 
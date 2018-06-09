<?php
use \core\dispatch;
use \core\route;
//
// セッションスタート
//
session_start();
//
// 初期設定
//
$setting = require('../chopstick/setting.php');
define('CS_APPLICATION_KEY', $setting['application_key']);
define('CS_BASE_DIR', $setting['chopstick_dir']);
define('CS_HOME_DIR', $setting['public_dir']);
$CS_MODE = ((getenv('CS_MODE') === false) ? $setting['mode'] : getenv('CS_MODE'));
define('CS_MODE',	$CS_MODE);
//
// composer の Autoloader を利用
//
require_once CS_BASE_DIR.'/vendor/autoload.php';
//
// コントローラー起動
//
$route = new route();
$route->query();
//
// コントローラー呼出
//
dispatch::run($route->controller_name, $route->action_name, $route->params);

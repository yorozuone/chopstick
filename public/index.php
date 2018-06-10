<?php
use \core\dispatch;
use \core\route;
// セッションスタート
session_start();
// 初期設定
$setting = require('../chopstick/setting.php');
define('CS_APPLICATION_KEY',    $setting['application_key']);
define('CS_BASE_DIR',           $setting['chopstick_dir']);
define('CS_HOME_DIR',           $setting['public_dir']);
define('CS_MODE',	            $setting['mode']);
// composer の Autoloader を利用
require_once CS_BASE_DIR.'/vendor/autoload.php';
// コントローラー起動
$route = new route();
$route->query();
// コントローラー呼出
dispatch::run($route);

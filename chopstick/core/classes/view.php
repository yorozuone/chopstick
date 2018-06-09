<?php
namespace core;

class view
{
    const GLOBALVARS_NAME = 'CORE_VIEW';
    //
    public $paths = '';             // テンプレートパス設定
    public $cache = false;          // キャッシュ設定
    public $debug = true;           // デバッグ設定
    //
    private $function = array();    // 拡張関数
    private $filter = array();      // 拡張フィルター
    //
    // --------------------------------------------------------------------------------
    // コンストラクタ実行
    // --------------------------------------------------------------------------------
    //
    function __construct()
    {
        // config 読込
        $config = config::read('view');
        // config 反映
        $this->paths    = $config['paths'];
        $this->cache    = $config['cache'];
        $this->debug    = $config['debug'];
        $this->function = $config['function'];
        $this->filter   = $config['filter'];
    }
    //
    // --------------------------------------------------------------------------------
    // レンダー実行
    // --------------------------------------------------------------------------------
    //
    public function render($src, $vars=array(), $mode=1)
    {
        //
        // globalvars 変数取得
        //
        $vars['GLOBALVARS'] = globalvars::get_values();
        //
        // loader 設定
        //
        if ($mode == 1)
        {
            $loader = new \Twig_Loader_Filesystem($this->paths);
        }
        else
        {
            $loader = new \Twig_Loader_String();
        }
        //
        // 環境設定
        //
        $twig = new \Twig_Environment
        (
            $loader, array
            (
                'cache' => $this->cache,
                'debug' => $this->debug,
            )
        );
        //
        // Twig Function Extension 追加
        //
        foreach ($this->function as $k => $v)
        {
            $twig->addFunction
                (
                    new \Twig_SimpleFunction($k, $v[0], isset($v[1]) ? $v[1] : array())
                );
        }
        //
        // Twig Filter Extension 追加
        //
        foreach ($this->filter as $k => $v)
        {
            $twig->addFilter
                (
                    new \Twig_SimpleFilter($k, $v[0], isset($v[1]) ? $v[1] : array())
                );
        }
        //
        // テキストから変換する場合は、構文チェックを行う
        //
        if ($mode != 1)
        {
            try
            {
                $twig->parse($twig->tokenize(new \Twig_Source($src, 'twig')));
            }
            catch (\Twig_Error_Syntax $e)
            {
                if (CS_MODE=='pdoduction')
                {
                    return '[表示できませんでした。]';
                }
                else
                {
                    return $e->getMessage();
                }
            }
        }
        //
        //
        //
        return $twig->render($src, $vars);
    }
}
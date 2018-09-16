<?php
namespace app;

use \core\view;

class block
{
    protected $page_id              = '';
    protected $composer_block_key   = '';
    //
    public static $block_name           = '';
    public static $block_description    = '';
    public static $block_version        = '';
    // --------------------------------------------------------------------------------
    // コンストラクタ
    // --------------------------------------------------------------------------------
    public function __construct($page_id, $composer_block_key)
    {
        $this->page_id = $page_id;
        $this->composer_block_key = $composer_block_key;
        //
        $this->before();
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function before()
    {
        //
    }
    // --------------------------------------------------------------------------------
    // ページId を設定
    // --------------------------------------------------------------------------------
    public function set_page_id($page_id)
    {
        $this->page_id = $page_id;
    }
    // --------------------------------------------------------------------------------
    // field 名の取得
    // --------------------------------------------------------------------------------
    public function get_field_name($name)
    {
        return $this->composer_block_key.'_'.$name;
    }
}
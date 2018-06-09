<?php
namespace app;

use \core\view;

class block extends \core\fieldset
{
    protected $page_id = '';
    protected $composer_block_key = '';
    //
    public static $title = '';
    public static $description = '';
    public static $version = '';
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
    //
    // --------------------------------------------------------------------------------
    public function set_page_id($page_id)
    {
        $this->page_id = $page_id;
    }
}
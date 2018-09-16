<?php
namespace app\model\controller\admin\block;

use \core\db;
use \core\file;

class base extends \core\fieldset
{
    // ################################################################################
    // コンストラクタ
    // ################################################################################
    public function __construct()
    {
        parent::__construct();
        //
        $this->append('block_key',          'ブロック・キー');
        //
        $this->append('name',               'ブロック名');
        $this->append('description',        '説明');
        $this->append('install_version',    'インストール・バージョン');
        //
        $this->append('version',            'バージョン');
    }
    // ################################################################################
    // データ操作
    // ################################################################################
    // --------------------------------------------------------------------------------
    // データ操作（読込）
    // --------------------------------------------------------------------------------
    public function read()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    block_key,
    name,
    description,
    install_version
FROM
    cs_block
WHERE
    block_key = :block_key;
EOT;
        $sql_params = array
        (
            ':block_key' => $this->get_value('block_key'),
        );
        $rs = $con->query($sql, $sql_params);
        //
        if (!isset($rs[0]))
        {
            return false;
        }        
        $this->set_values($rs[0]);
        //
        return true;
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function get_class_path()
    {
        return '\\app\\block\\'.$this->get_value('block_key').'\\controller';
    }
    // --------------------------------------------------------------------------------
    // データ操作（読込）
    // --------------------------------------------------------------------------------
    public function read_from_class()
    {
        $block_class = $this->get_class_path();
        if (!$block_class)
        {
            return false;
        }
        $this->set_value('name',        $block_class::$block_name);
        $this->set_value('description', $block_class::$block_description);
        $this->set_value('version',     $block_class::$block_version);
        return true;
    }
}
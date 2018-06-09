<?php
namespace app\model\controller\admin\composer_block;

use \core\db;

class base extends \core\fieldset
{
    // ################################################################################
    // コンストラクタ
    // ################################################################################
    public function __construct()
    {
        parent::__construct();
        //
        $this->append('composer_block_key', 'コンポーザー・ブロック・キー');
        $this->append('composer_key',       'コンポーザー・キー');
        //
        $this->append('block_key',          'ブロック・キー');
        $this->append('caption',            'ブロック名');
        $this->append('description',        '説明');
        $this->append('datasource',         'データソース');
        $this->append('order_at',           '順序');
    }
    // ################################################################################
    // データ操作
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 読込
    // --------------------------------------------------------------------------------
    public function read()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    composer_key,
    composer_block_key,
    block_key,
    caption,
    description,
    datasource
FROM
    cs_composer_block
WHERE
    composer_key = :composer_key AND
    composer_block_key = :composer_block_key;
EOT;
        $sql_params = array
        (
            ':composer_key'         => $this->get_value('composer_key'),
            ':composer_block_key'   => $this->get_value('composer_block_key'),
        );
        $rs = $con->query($sql, $sql_params);
        //
        if (isset($rs[0]) == false)
        {
            return false;
        }
        $this->set_values($rs[0]);
        //
        return true;
    }
}
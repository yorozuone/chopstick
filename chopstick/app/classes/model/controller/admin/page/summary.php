<?php
namespace app\model\controller\admin\page;

use \core\db;

class summary extends \core\fieldset
{
    //
    // --------------------------------------------------------------------------------
    // コンストラクタ
    // --------------------------------------------------------------------------------
    //
    public function __construct()
    {
        parent::__construct();
        //
        $this->append('parent_page_id', '親ページ', 0);
    }
    //
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    //
    public function fetch_all()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    page_id,
    parent_page_id,
    composer_key,
    page_title,
    reserved,
    created_at,
    updated_at
FROM
    cs_page
WHERE
    parent_page_id = :parent_page_id
ORDER BY
    order_at,
    page_id
EOT;
        $sql_params = array
        (
            ':parent_page_id'   => $this->get_value('parent_page_id'),
        );
        return $con->query($sql, $sql_params);
    }
}
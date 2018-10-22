<?php
namespace app\model\controller\admin\user;

use \core\db;

class summary extends \core\fieldset
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function fetch_all()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    cs_user.user_id,
    cs_user.username,
    cs_user.email,
    cs_group.caption as group_caption,
    cs_user.updated_at
FROM
    cs_user
LEFT JOIN
    cs_group
ON
    cs_user.group_key = cs_group.group_key
ORDER BY
    cs_user.username
EOT;
        return $con->query($sql);
    }
}
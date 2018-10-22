<?php
namespace app\cscms\block\cs_markdown;

use \core\db;

class model extends \core\fieldset
{
    // ################################################################################
    // コンストラクタ
    // ################################################################################
    public function __construct()
    {
        $this->append('markdowntext', 'markdowntext', '');
        $this->set_description('markdowntext', 'Markdown を入力してください。');
    }
    // ################################################################################
    //
    // ################################################################################
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function install()
    {
        $con = new db();
        //
        $sql = <<< EOT
CREATE TABLE `cs_block_markdown` (
	`page_id` INT(11) NOT NULL,
	`composer_block_key` VARCHAR(255) NOT NULL,
	`markdowntext` TEXT NULL,
	`created_at` DATETIME NULL DEFAULT NULL,
	`updated_at` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`page_id`, `composer_block_key`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
EOT;
        $rs = $con->query($sql);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function remove()
    {
        $con = new db();
        //
        $sql = <<< EOT
DROP TABLE `cs_block_markdown`;
EOT;
        $rs = $con->query($sql);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function read($page_id, $composer_block_key)
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    markdowntext
FROM
    cs_block_markdown
WHERE
    page_id = :page_id AND
    composer_block_key = :composer_block_key;
EOT;
        $sql_params = array
        (
            ':page_id' => $page_id,
            ':composer_block_key' => $composer_block_key,
        );
        $rs = $con->query($sql, $sql_params);
        //
        if (isset($rs[0]) == false)
        {
            return false;
        }
        $this->set_values($rs[0]);
        return true;
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function update($page_id, $composer_block_key)
    {
        $con = new db();
        //
        $sql_1 = <<< EOT
SELECT
    COUNT(*) AS CNT
FROM
    cs_block_markdown
WHERE
    page_id = :page_id AND
    composer_block_key = :composer_block_key;
EOT;
        $sql_params_1 = array
        (
            ':page_id' => $page_id,
            ':composer_block_key' => $composer_block_key,
        );
        $rs_1 = $con->query($sql_1, $sql_params_1);
        //
        if ($rs_1[0]['CNT'] == 0)
        {
            $sql_2 = <<< EOT
INSERT INTO cs_block_markdown
(
    page_id,
    composer_block_key,
    markdowntext,
    created_at,
    updated_at
)
VALUES
(
    :page_id,
    :composer_block_key,
    :markdowntext,
    NOW(),
    NOW()
);
EOT;
            $sql_params_2 = array
            (
                ':page_id' => $page_id,
                ':composer_block_key' => $composer_block_key,
                ':markdowntext' => $this->get_value('markdowntext'),
            );
            $rs = $con->query($sql_2, $sql_params_2);
        }
        else
        {
            $sql_3 = <<< EOT
UPDATE
    cs_block_markdown
SET
    markdowntext = :markdowntext,
    updated_at = NOW()
WHERE
    page_id = :page_id AND
    composer_block_key = :composer_block_key;
EOT;
            $sql_params_3 = array
            (
                ':page_id' => $page_id,
                ':composer_block_key' => $composer_block_key,
                ':markdowntext' => $this->get_value('markdowntext'),
            );
            $rs = $con->query($sql_3, $sql_params_3);
        }
        return true;
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function delete($page_id, $composer_block_key)
    {
        $con = new db();
        //
        $sql = <<< EOT
DELETE
FROM
    cs_block_markdown
WHERE
    page_id = :page_id AND
    composer_block_key = :composer_block_key;
EOT;
        $sql_params = array
        (
            ':page_id' => $page_id,
            ':composer_block_key' => $composer_block_key,
        );
        $rs = $con->query($sql, $sql_params);
        return true;
    }
}
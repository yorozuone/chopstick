<?php
namespace app\block;

use \core\db;
use \core\input;
use \core\view;

class cs_twigtext extends \app\block
{
    public static $block_name           = 'Chostick Twig Block';
    public static $block_description    = 'Twig を入力します。';
    public static $block_version        = '1.0.0';
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function before()
    {
        $this->append('twigtext', 'twigtext', 'twigtext', $this->composer_block_key);
        $this->set_description('twigtext', 'Twig を入力してください。');
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function install()
    {
        $con = new db();
        //
        $sql = <<< EOT
CREATE TABLE `cs_block_cs_twigtext` (
	`page_id` INT(11) NOT NULL,
	`composer_block_key` VARCHAR(255) NOT NULL,
	`twigtext` TEXT NULL,
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
DROP TABLE `cs_block_cs_twigtext`;
EOT;
        $rs = $con->query($sql);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function read()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    twigtext
FROM
    cs_block_cs_twigtext
WHERE
    page_id = :page_id AND
    composer_block_key = :composer_block_key;
EOT;
        $sql_params = array
        (
            ':page_id' => $this->page_id,
            ':composer_block_key' => $this->composer_block_key,
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
    // --------------------------------------------------------------------------------
    // 作成
    // --------------------------------------------------------------------------------
    public function create()
    {
        $con = new db();
        //
        $sql = <<< EOT
INSERT INTO cs_block_cs_twigtext
(
    page_id,
    composer_block_key,
    twigtext,
    created_at,
    updated_at
)
VALUES
(
    :page_id,
    :composer_block_key,
    :twigtext,
    NOW(),
    NOW()
);
EOT;
        $sql_params = array
        (
            ':page_id' => $this->page_id,
            ':composer_block_key' => $this->composer_block_key,
            ':twigtext' => $this->get_value('twigtext'),
        );
        $rs = $con->query($sql, $sql_params);
        //
        return true;
    }
    // --------------------------------------------------------------------------------
    // 更新
    // --------------------------------------------------------------------------------
    public function update()
    {
        $con = new db();
        //
        $sql = <<< EOT
INSERT INTO cs_block_cs_twigtext
(
    page_id,
    composer_block_key,
    twigtext,
    created_at,
    updated_at
)
VALUES
(
    :page_id,
    :composer_block_key,
    :twigtext_1,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    twigtext = :twigtext_2,
    updated_at = NOW()
EOT;
        $sql_params = array
        (
            ':page_id' => $this->page_id,
            ':composer_block_key' => $this->composer_block_key,
            //
            ':twigtext_1' => $this->get_value('twigtext'),
            ':twigtext_2' => $this->get_value('twigtext'),
        );
        $rs = $con->query($sql, $sql_params);
        //
        return true;
    }
    // --------------------------------------------------------------------------------
    // 更新
    // --------------------------------------------------------------------------------
    public function check()
    {
        $this->validate('required', 'twigtext');
        //
        return $this->is_valid;
    }
    // --------------------------------------------------------------------------------
    // 削除
    // --------------------------------------------------------------------------------
    public function delete()
    {
        $con = new db();
        //
        $sql = <<< EOT
DELETE
FROM
    cs_block_cs_twigtext
WHERE
    page_id = :page_id AND
    composer_block_key = :composer_block_key;
EOT;
        $sql_params = array
        (
            ':page_id' => $this->page_id,
            ':composer_block_key' => $this->composer_block_key,
        );
        $rs = $con->query($sql, $sql_params);
        //
        return true;
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function block_post()
    {
        $input = input::post($this->composer_block_key.'_twigtext');
        $this->set_value('twigtext', $input[$this->composer_block_key.'_twigtext']);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function get_entry_html()
    {
        $html = <<< EOT
<div class="form-group">
    <label for="{{ composer_block_key }}_twigtext">{{ values.twigtext.label }}</label>
    <textarea class="form-control" id="{{ composer_block_key }}_twigtext" name="{{ composer_block_key }}_twigtext" placeholder="{{ values.twigtext.description }}" rows="10">{{ values.twigtext.value }}</textarea>
    {% if values.twigtext.error_message != '' %}
        <p class="help-block">
            <div class="alert alert-danger" role="alert">{{ values.twigtext.error_message }}</div>
        </p>
    {% endif %}
</div>
EOT;
        $view = new view();
        $vars = array
        (
            'composer_block_key' => $this->composer_block_key,
            'values' => $this->to_array(),
        );
        return $view->render($html, $vars, 2);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function get_confirm_html()
    {
        $html = <<< EOT
<div class="form-group">
    <label for="{{ composer_block_key }}_twigtext">{{ values.twigtext.label }}</label>
    <pre>{{ values.twigtext.value }}</pre>
</div>
EOT;
        $view = new view();
        $vars = array
        (
            'values' => $this->to_array(),
        );
        return $view->render($html, $vars, 2);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function get_view_html()
    {
        $this->read();
        //        
        $view = new view();
        return $view->render($this->get_value('twigtext'), array(), 2);
    }
}
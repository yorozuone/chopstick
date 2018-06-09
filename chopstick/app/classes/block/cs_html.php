<?php
namespace app\block;

use \core\db;
use \core\input;
use \core\view;

class cs_html extends \app\block
{
    public static $block_name           = 'Chostick HTML Block';
    public static $block_description    = 'HTML を入力します。';
    public static $block_version        = '1.0.0';
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function before()
    {
        parent::before();
        //
        $this->append('html', 'html', 'html', $this->composer_block_key);
        $this->set_description('html', 'HTML を入力してください。');
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function install()
    {
        $con = new db();
        //
        $sql = <<< EOT
CREATE TABLE `cs_block_cs_html` (
	`page_id` INT(11) NOT NULL,
	`composer_block_key` VARCHAR(255) NOT NULL,
	`html` TEXT NULL,
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
DROP TABLE `cs_block_cs_html`;
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
    html
FROM
    cs_block_cs_html
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
INSERT INTO cs_block_cs_html
(
    page_id,
    composer_block_key,
    html,
    created_at,
    updated_at
)
VALUES
(
    :page_id,
    :composer_block_key,
    :html,
    NOW(),
    NOW()
);
EOT;
        $sql_params = array
        (
            ':page_id' => $this->page_id,
            ':composer_block_key' => $this->composer_block_key,
            ':html' => $this->get_value('html'),
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
INSERT INTO cs_block_cs_html
(
    page_id,
    composer_block_key,
    html,
    created_at,
    updated_at
)
VALUES
(
    :page_id,
    :composer_block_key,
    :html_1,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    html = :html_2,
    updated_at = NOW()
EOT;
        $sql_params = array
        (
            ':page_id' => $this->page_id,
            ':composer_block_key' => $this->composer_block_key,
            //
            ':html_1' => $this->get_value('html'),
            ':html_2' => $this->get_value('html'),
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
        $this->validate('required', 'html');
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
    cs_block_cs_html
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
        $input = input::post($this->composer_block_key.'_html');
        $this->set_value('html', $input[$this->composer_block_key.'_html']);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function get_entry_html()
    {
        $html = <<< EOT
<div class="form-group">
    <label for="{{ composer_block_key }}_html">{{ values.html.label }}</label>
    <textarea class="form-control" id="{{ composer_block_key }}_html" name="{{ composer_block_key }}_html" placeholder="{{ values.html.description }}" rows="10">{{ values.html.value }}</textarea>
    {% if values.html.error_message != '' %}
        <p class="help-block">
            <div class="alert alert-danger" role="alert">{{ values.html.error_message }}</div>
        </p>
    {% endif %}
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
    public function get_confirm_html()
    {
        $html = <<< EOT
<div class="form-group">
    <label for="{{ composer_block_key }}_html">{{ values.html.label }}</label>
    <pre>{{ values.html.value }}</pre>
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
        return $view->render($this->get_value('html'), array(), 2);
    }
}
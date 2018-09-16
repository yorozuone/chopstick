<?php
namespace app\block\cs_markdowntext;

use \core\db;
use \core\input;
use \core\view;

class controller extends \app\block
{
    public static $block_name           = 'Chostick Markdown Block';
    public static $block_description    = 'Markdown を入力します。';
    public static $block_version        = '1.0.0';
    //
    private $dataset;
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function before()
    {
        $this->dataset = new \core\fieldset();
        //
        $this->dataset->append('markdowntext', 'markdowntext', '');
        $this->dataset->set_description('markdowntext', 'Markdown を入力してください。');
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function install()
    {
        $con = new db();
        //
        $sql = <<< EOT
CREATE TABLE `cs_block_cs_markdowntext` (
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
DROP TABLE `cs_block_cs_markdowntext`;
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
    markdowntext
FROM
    cs_block_cs_markdowntext
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
        $this->dataset->set_values($rs[0]);
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
INSERT INTO cs_block_cs_markdowntext
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
        $sql_params = array
        (
            ':page_id' => $this->page_id,
            ':composer_block_key' => $this->composer_block_key,
            ':markdowntext' => $this->dataset->get_value('markdowntext'),
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
INSERT INTO cs_block_cs_markdowntext
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
    :markdowntext_1,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    markdowntext = :markdowntext_2,
    updated_at = NOW()
EOT;
        $sql_params = array
        (
            ':page_id' => $this->page_id,
            ':composer_block_key' => $this->composer_block_key,
            //
            ':markdowntext_1' => $this->dataset->get_value('markdowntext'),
            ':markdowntext_2' => $this->dataset->get_value('markdowntext'),
        );
        $rs = $con->query($sql, $sql_params);
        //
        return true;
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
    cs_block_cs_markdowntext
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
        $input = input::post($this->get_field_name('markdowntext'));
        $this->dataset->set_value('markdowntext', $input[$this->get_field_name('markdowntext')]);
    }
    // --------------------------------------------------------------------------------
    // 更新
    // --------------------------------------------------------------------------------
    public function block_check()
    {
        $this->dataset->validate('required', 'markdowntext');
        return $this->dataset->is_valid;
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function get_entry_html()
    {
        $html = <<< EOT
<div class="form-group">
    <label for="{{ composer_block_key }}_markdowntext">{{ values.markdowntext.label }}</label>
    <textarea class="form-control" id="{{ composer_block_key }}_markdowntext" name="{{ composer_block_key }}_markdowntext" placeholder="{{ values.markdowntext.description }}" rows="10">{{ values.markdowntext.value }}</textarea>
    {% if values.markdowntext.error_message != '' %}
        <p class="help-block">
            <div class="alert alert-danger" role="alert">{{ values.markdowntext.error_message }}</div>
        </p>
    {% endif %}
</div>
EOT;
        $view = new view();
        $vars = array
        (
            'composer_block_key' => $this->composer_block_key,
            'values' => $this->dataset->to_array(),
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
    <label for="{{ composer_block_key }}_markdowntext">{{ values.markdowntext.label }}</label>
    <pre>{{ values.markdowntext.value }}</pre>
</div>
EOT;
        $view = new view();
        $vars = array
        (
            'values' => $this->dataset->to_array(),
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
        return $view->render($this->dataset->get_value('markdowntext'), array(), 2);
    }
}
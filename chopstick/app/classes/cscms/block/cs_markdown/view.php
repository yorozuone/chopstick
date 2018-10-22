<?php
namespace app\cscms\block\cs_markdown;

use \core\db;

class view extends \core\view
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function head_top_html($page_id, $composer_key, $dataset)
    {
        return '';
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function head_bottom_html($page_id, $composer_block_key, $dataset)
    {
        return '';
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function body_top_html($page_id, $composer_block_key, $dataset)
    {
        return '';
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function body_bottom_html($page_id, $composer_block_key, $dataset)
    {
        return '';
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function body_edit_html($page_id, $composer_block_key, $dataset)
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
        $vars = array
        (
            'composer_block_key' => $composer_block_key,
            'values' => $dataset->to_array(),
        );
        return $this->render($html, $vars, 2);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function body_confirm_html($page_id, $composer_block_key, $dataset)
    {
        $html = <<< EOT
<div class="form-group">
    <label for="{{ composer_block_key }}_markdowntext">{{ values.markdowntext.label }}</label>
    <pre>{{ values.markdowntext.value }}</pre>
</div>
EOT;
        $vars = array
        (
            'values' => $dataset->to_array(),
        );
        return $this->render($html, $vars, 2);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function body_view_html($page_id, $composer_block_key, $dataset)
    {
        $parsedown = new \app\cscms\block\cs_markdown\parsedown;
        $dataset->read($page_id, $composer_block_key);
        return $parsedown->text($dataset->get_value('markdowntext'));
    }
}
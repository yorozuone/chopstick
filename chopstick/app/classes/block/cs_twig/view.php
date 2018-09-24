<?php
namespace app\block\cs_twig;

use \core\db;

class view extends \core\view
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function body_edit_html($page_id, $composer_block_key, $dataset)
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
    <label for="{{ composer_block_key }}_twigtext">{{ values.twigtext.label }}</label>
    <pre>{{ values.twigtext.value }}</pre>
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
        $dataset->read($page_id, $composer_key);
        return $this->render($dataset->get_value('twigtext'), array(), 2);
    }
}

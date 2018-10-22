<?php
namespace app\cscms\block\cs_twig;

use \core\db;
use \core\input;
use \core\view;

class controller extends \app\cscms\block\block
{
    public static $block_name           = 'Chostick Twig Block';
    public static $block_description    = 'Twig を入力します。';
    public static $block_version        = '1.0.0';
    //
    private $dataset;
    private $view;
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function before()
    {
        $this->dataset = new \app\cscms\block\cs_twig\model();
        $this->view = new \app\cscms\block\cs_twig\view();
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function install()
    {
        $this->dataset->install();
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function remove()
    {
        $this->dataset->remove();
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function read()
    {
        return $this->dataset->read($this->page_id, $this->composer_block_key);
    }
    // --------------------------------------------------------------------------------
    // 更新
    // --------------------------------------------------------------------------------
    public function update()
    {
        return $this->dataset->update($this->page_id, $this->composer_block_key);
    }
    // --------------------------------------------------------------------------------
    // 削除
    // --------------------------------------------------------------------------------
    public function delete()
    {
        return $this->dataset->delete($this->page_id, $this->composer_block_key);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function block_post()
    {
        $input = input::post($this->get_field_name('twigtext'));
        $this->dataset->set_value('twigtext', $input[$this->get_field_name('twigtext')]);
    }
    // --------------------------------------------------------------------------------
    // 更新
    // --------------------------------------------------------------------------------
    public function block_check()
    {
        return $this->dataset->is_valid;
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function body_edit_html()
    {
        return $this->view->body_edit_html($this->page_id, $this->composer_block_key, $this->dataset);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function body_confirm_html()
    {
        return $this->view->body_confirm_html($this->page_id, $this->composer_block_key, $this->dataset);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function body_view_html()
    {
        return $this->view->body_view_html($this->page_id, $this->composer_block_key, $this->dataset);
    }
}
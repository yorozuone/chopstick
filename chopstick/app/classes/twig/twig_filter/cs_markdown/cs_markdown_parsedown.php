<?php
namespace app\twig\twig_filter\cs_markdown;

class cs_markdown_parsedown extends \Parsedown
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    protected function blockTable($Line, array $Block = null)
    {
        $my_block = parent::blockTable($Line, $Block);
        if ($my_block == '')
        {
            return;
        }
        $my_block['element']['attributes'] = array('class' => 'table table-striped table-bordered');
        return $my_block;
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    protected function inlineImage($Excerpt)
    {
        $my_params = parent::inlineImage($Excerpt);
        $my_params['element']['attributes']['class'] = 'img-thumbnail';
        return $my_params;
    }
}
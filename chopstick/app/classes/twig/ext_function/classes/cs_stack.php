<?php
namespace app\twig\ext_function\classes;

use \core\db;
use \core\view;

class cs_stack
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function render($config = array())
    {
        $config = is_array($config) ? $config : array();
        $config = array_replace_recursive
        (
            array
            (
                'id'        => 'cs_stack',
                'class'     => 'cs_stack',
                'template'  => 'default',
                'stack_key' => '',
            ),
            $config
        );
        $vars = array
        (
            'html' => self::read($config['stack_key']),
        );
        $vars = array_merge_recursive($config, $vars);
        //
        $v = new view();
        return $v->render('twig/ext_function/cs_stack/'.$config['template'].'.twig', $vars);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    static function read($stack_key)
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    content
FROM
    cs_stack
WHERE
    stack_key = :stack_key
EOT;
        $sql_params = array
        (
            ':stack_key' => $stack_key,
        );
        $rs = $con->query($sql, $sql_params);
        //
        return isset($rs[0]['content']) ? $rs[0]['content'] : '';
    }
}
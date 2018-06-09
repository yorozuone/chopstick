<?php
namespace core\validation;

class alnum
{
    //
    // --------------------------------------------------------------------------------
    // 半角英数チェック
    // --------------------------------------------------------------------------------
    //
    public static function run($field, $args = array())
    {
        $field_name = $args[0];
        //
        $ommit = array();
        if (isset($args[1]))
        {
            $ommit = $args[1];
        }
        //
        $label = $field->get_label($field_name);
        $value = $field->get_value($field_name);
        //
        if (count($ommit) > 0)
        {
            $value = str_replace($ommit, '', $value);
        }
        if (!ctype_alnum($value))
        {
            $field->set_error($field_name, $label.'は、半角英数で入力してください');
            return false;
        }
        return true;
    }
}

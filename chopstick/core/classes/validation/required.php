<?php
namespace core\validation;

class required
{
    //
    // --------------------------------------------------------------------------------
    // 必須チェック
    // --------------------------------------------------------------------------------
    //
    public static function run($field, $args = array())
    {
        $field_name = $args[0];
        //
        $label = $field->get_label($field_name);
        $value = $field->get_value($field_name);
        //
        if ($value == '')
        {
            $field->set_error($field_name, $label.'は、必ず入力してください');
            return false;
        }
        return true;
    }
}

<?php
namespace core\validation;

class max_length
{
    //
    // --------------------------------------------------------------------------------
    // 文字の最長チェック
    // --------------------------------------------------------------------------------
    //
    public static function run($field, $args = array())
    {
        $field_name = $args[0];
        $length = $args[1];
        //
        $label = $field->get_label($field_name);
        $value = $field->get_value($field_name);
        //
        if (strlen($value) > $length)
        {
            $field->set_error($field_name, $label.'は、'.$length.'文字以上で入力してください。');
            return false;
        }
        return true;
    }
}

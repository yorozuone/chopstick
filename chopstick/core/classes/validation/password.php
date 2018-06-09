<?php
namespace core\validation;

class password
{
    // --------------------------------------------------------------------------------
    // パスワードチェック
    // --------------------------------------------------------------------------------
    public static function run($field, $args = array())
    {
        $field_name = $args[0];
        //
        $label = $field->get_label($field_name);
        $value = $field->get_value($field_name);
        //
        if (strlen($value) < 8)
        {
            $field->set_error($field_name, $label.'は、8文字以上で入力してください。');
            return false;
        }
        if (strlen($value) > 32)
        {
            $field->set_error($field_name, $label.'は、32文字以内で入力してください。');
            return false;
        }
        if(!preg_match("#[0-9]+#",$value))
        {
            $field->set_error($field_name, $label.'は、数値を含んで入力してください。');
            return false;
        }
        if(!preg_match("#[A-Z]+#",$value))
        {
            $field->set_error($field_name, $label.'は、大文字のアルファベットを含んで入力してください。');
            return false;
        }
        if(!preg_match("#[a-z]+#",$value))
        {
            $field->set_error($field_name, $label.'は、小文字のアルファベットを含んで入力してください。');
            return false;
        }
        return true;
    }
}

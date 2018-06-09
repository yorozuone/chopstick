<?php
namespace core\validation;

class email
{
    //
    // --------------------------------------------------------------------------------
    // メールアドレスチェック
    // --------------------------------------------------------------------------------
    //
    public static function run($field, $args = array())
    {
        $field_name = $args[0];
        //
        $label = $field->get_label($field_name);
        $value = $field->get_value($field_name);
        //
        $check_dns = isset($option[0]) ? $option[0] : false;
        //
        switch (true)
        {
            case false === filter_var($value, FILTER_VALIDATE_EMAIL):
            case !preg_match('/@(?!\[)(.++)\z/', $value, $m):
                $field->set_error($field_name, $label.'は、メールアドレスとして認識できません');
                return false;
            case !$check_dns:
            case checkdnsrr($m[1], 'MX'):
            case checkdnsrr($m[1], 'A'):
            case checkdnsrr($m[1], 'AAAA'):
                return true;
            default:
                $field->set_error($field_name, $label.'は、不正なメールアドレスです。');
                return false;
        }
    }
}

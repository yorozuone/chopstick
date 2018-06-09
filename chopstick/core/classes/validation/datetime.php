<?php
namespace core\validation;

class datetime
{
    //
    // --------------------------------------------------------------------------------
    // 日時チェック
    // --------------------------------------------------------------------------------
    //
    public static function run($field, $args = array())
    {
        $field_name = $args[0];
        //
        $label = $field->get_label($field_name);
        $value = $field->get_value($field_name);
        //
        if ($value !== date("Y-m-d H:i:s", strtotime($value)))
        {
            $field->set_error($field_name, $label.'は、日時として認識できません');
            return false;
        }
        return true;
    }
}

<?php
namespace core;

use \core\input;

class field
{
    public $is_valid        = true;
    //
    private $name           = '';
    private $label          = '';
    private $value          = '';
    private $description    = '';
    //
    private $error_message  = '';
    // --------------------------------------------------------------------------------
    // コンストラクト
    // --------------------------------------------------------------------------------
    public function __construct($name, $label = '', $value = '')
    {
        debug::trace('[core/field/__construct] : 開始 : name=>'.$name);
        $this->name     = $name;
        $this->label    = $label;
        $this->value    = $value;
    }
    // --------------------------------------------------------------------------------
    // 配列に変換
    // --------------------------------------------------------------------------------
    public function to_array()
    {
        debug::trace('[core/field/to_array] : 開始');
        $obj = array();
        //
        $obj['is_valid']         = $this->is_valid;
        //
        $obj['name']             = $this->get_name();
        $obj['label']            = $this->get_label();
        $obj['value']            = $this->get_value();
        $obj['description']      = $this->get_description();
        //
        $obj['error_message']    = $this->get_error_message();
        //
        return $obj;
    }
    // --------------------------------------------------------------------------------
    // get method
    // --------------------------------------------------------------------------------
    public function get_is_valid()
    {
        debug::trace('[core/field/get_is_valid] : 開始');
        return $this->is_valid;
    }
    //
    public function get_name()
    {
        debug::trace('[core/field/get_name] : 開始');
        return $this->name;
    }
    //
    public function get_label()
    {
        debug::trace('[core/field/get_label] : 開始');
        return $this->label;
    }
    //
    public function set_label($value)
    {
        debug::trace('[core/field/set_label] : 開始');
        $this->label = $value;
    }
    //
    public function get_value()
    {
        debug::trace('[core/field/get_value] : 開始');
        return $this->value;
    }
    //
    public function set_value($value)
    {
        debug::trace('[core/field/set_value] : 開始');
        $this->value = $value;
    }
    //
    public function get_description()
    {
        debug::trace('[core/field/get_description] : 開始');
        return $this->description;
    }
    //
    public function set_description($value)
    {
        debug::trace('[core/field/set_description] : 開始');
        $this->description = $value;
    }
    //
    public function get_error_message()
    {
        debug::trace('[core/field/get_error_message] : 開始');
        return $this->error_message;
    }
    //
    public function set_error_message($error_message)
    {
        debug::trace('[core/field/set_error_message] : 開始');
        $this->is_valid = false;
        $this->error_message = $error_message;
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function post()
    {
        debug::trace('[core/field/post] : 開始');
        $input = input::post($this->name);
        if (isset($input[$this->name]))
        {
            if (is_array($input[$this->name]))
            {
                $this->value = $input[$this->name];
            }
            else
            {
                if (mb_check_encoding($input[$this->name], 'UTF-8'))
                {
                    $this->value = $input[$this->name];
                }
            }
        }
    }
}
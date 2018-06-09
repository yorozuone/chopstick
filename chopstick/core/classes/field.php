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
        $this->name     = $name;
        $this->label    = $label;
        $this->value    = $value;
    }
    // --------------------------------------------------------------------------------
    // 配列に変換
    // --------------------------------------------------------------------------------
    public function to_array()
    {
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
        return $this->is_valid;
    }
    //
    public function get_name()
    {
        return $this->name;
    }
    //
    public function get_label()
    {
        return $this->label;
    }
    //
    public function set_label($value)
    {
        $this->label = $value;
    }
    //
    public function get_value()
    {
        return $this->value;
    }
    //
    public function set_value($value)
    {
        $this->value = $value;
    }
    //
    public function get_description()
    {
        return $this->description;
    }
    //
    public function set_description($value)
    {
        $this->description = $value;
    }
    //
    public function get_error_message()
    {
        return $this->error_message;
    }
    //
    public function set_error_message($error_message)
    {
        $this->is_valid = false;
        $this->error_message = $error_message;
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function post()
    {
        $input = input::post($this->name);
        if (isset($input[$this->name]))
        {
            $this->value = $input[$this->name];
        }
    }
}
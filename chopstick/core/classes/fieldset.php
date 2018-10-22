<?php
namespace core;

use \core\field;

class fieldset
{
    public $is_valid = true;
    //
    private $fields = array();
    // ################################################################################
    // コンストラクタ
    // ################################################################################
    public function __construct()
    {
        debug::trace('[core/fieldset/__construct] : 開始');
    }
    // --------------------------------------------------------------------------------
    // フィールドを追加
    // --------------------------------------------------------------------------------
    public function append($name, $label = '', $value = '')
    {
        debug::trace('[core/fieldset/append] : 開始');
        $obj = new field($name, $label, $value);
        $this->fields[$name] = $obj;
    }
    // --------------------------------------------------------------------------------
    // フィールド情報を配列に変換
    // --------------------------------------------------------------------------------
    public function to_array()
    {
        debug::trace('[core/fieldset/to_array] : 開始');
        $obj = array();
        foreach($this->fields as $k => $v)
        {
            $obj[$k] = $v->to_array();
        }
        //
        return $obj;
    }
    // --------------------------------------------------------------------------------
    // 送信データを POST で受信
    // --------------------------------------------------------------------------------
    public function post($name = '')
    {
        debug::trace('[core/fieldset/post] : 開始');
        if($name == '')
        {
            foreach($this->fields as $v)
            {
                $v->post();
            }
        }  
        else
        {
            if (!is_array($name))
            {
                $name = array($name);
            }
            foreach($name as $v)
            {
                if(isset($this->fields[$v]))
                {
                    $this->fields[$v]->post();
                }
            }
        }
        return true;
    }
    // --------------------------------------------------------------------------------
    // 値を設定・取得
    // --------------------------------------------------------------------------------
    public function set_value($name, $value)
    {
        debug::trace('[core/fieldset/set_value] : 開始');
        $this->fields[$name]->set_value($value);
    }
    public function get_value($name)
    {
        debug::trace('[core/fieldset/get_value] : 開始');
        return $this->fields[$name]->get_value();
    }
    // --------------------------------------------------------------------------------
    // 複数の値を設定・取得
    // --------------------------------------------------------------------------------
    public function set_values($values)
    {
        debug::trace('[core/fieldset/set_values] : 開始');
        foreach($values as $k => $v)
        {
            if (isset($this->fields[$k]))
            {
                $this->fields[$k]->set_value($v);
            }
        }
    }
    public function get_values()
    {
        $obj = array();
        foreach($this->fields as $v)
        {
            $obj[$v->get_name()] = $v->get_value();
        }
        return $obj;
    }
    // --------------------------------------------------------------------------------
    // ラベル設定・取得
    // --------------------------------------------------------------------------------
    public function set_label($name, $value)
    {
        debug::trace('[core/fieldset/set_label] : 開始');
        $this->fields[$name]->set_label($value);
    }
    public function get_label($name)
    {
        debug::trace('[core/fieldset/get_label] : 開始');
        return $this->fields[$name]->get_label();
    }
    // --------------------------------------------------------------------------------
    // 説明設定・取得
    // --------------------------------------------------------------------------------
    public function set_description($name, $value)
    {
        debug::trace('[core/fieldset/set_description] : 開始');
        $this->fields[$name]->set_description($value);
    }
    public function get_description($name)
    {
        debug::trace('[core/fieldset/get_description] : 開始');
        return $this->fields[$name]->get_description();
    }
    // --------------------------------------------------------------------------------
    // 全てのエラーメッセージを取得
    // --------------------------------------------------------------------------------
    public function get_error_messages()
    {
        debug::trace('[core/fieldset/get_error_messages] : 開始');
        $obj = array();
        foreach($this->fields as $k => $v)
        {
            $obj[$k] = $v->get_error_message();
        }
        return $obj;
    }
    // --------------------------------------------------------------------------------
    // エラーを設定
    // --------------------------------------------------------------------------------
    public function set_error($name, $error_message)
    {
        debug::trace('[core/fieldset/set_error] : 開始');
        $this->fields[$name]->set_error_message($error_message);
    }
    // --------------------------------------------------------------------------------
    // 検証実行
    // --------------------------------------------------------------------------------
    public function validate(...$args)
    {
        debug::trace('[core/fieldset/svalidate] : 開始');
        $method = '\\core\\validation\\'.$args[0];
        if ($method::run($this, array_slice($args, 1)) == false)
        {
            $this->is_valid = false;
        }
        return $this->is_valid;
    }
}

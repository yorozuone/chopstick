<?php
namespace core;

use \core\field;

class fieldset
{
    public $is_valid = true;
    private $fields = array();
    // ################################################################################
    // コンストラクタ
    // ################################################################################
    public function __construct()
    {
        //
    }
    // --------------------------------------------------------------------------------
    // フィールドを追加
    // --------------------------------------------------------------------------------
    public function append($name, $label = '', $value = '')
    {
        $obj = new field($name, $label, $value);
        //
        $this->fields[$name] = $obj;
    }
    // --------------------------------------------------------------------------------
    // フィールド情報を配列に変換
    // --------------------------------------------------------------------------------
    public function to_array()
    {
        $obj = array();
        //
        foreach($this->fields as $k => $v)
        {
            $obj[$k] = $v->to_array();
        }
        //
        return $obj;
    }
    //
    // --------------------------------------------------------------------------------
    // 送信データを POST で受信
    // --------------------------------------------------------------------------------
    public function post($name = '')
    {
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
        $this->fields[$name]->set_value($value);
    }
    public function get_value($name)
    {
        return $this->fields[$name]->get_value();
    }
    // --------------------------------------------------------------------------------
    // 複数の値を設定・取得
    // --------------------------------------------------------------------------------
    public function set_values($values)
    {
        foreach($values as $k => $v)
        {
            $this->fields[$k]->set_value($v);
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
    // ラベル取得
    // --------------------------------------------------------------------------------
    public function get_label($name)
    {
        return $this->fields[$name]->get_label();
    }
    // --------------------------------------------------------------------------------
    // ラベル取得
    // --------------------------------------------------------------------------------
    public function set_label($name, $value)
    {
        $this->fields[$name]->set_label($value);
    }
    // --------------------------------------------------------------------------------
    // 取得
    // --------------------------------------------------------------------------------
    public function get_description($name)
    {
        return $this->fields[$name]->get_description();
    }
    // --------------------------------------------------------------------------------
    // 取得
    // --------------------------------------------------------------------------------
    public function set_description($name, $value)
    {
        $this->fields[$name]->set_description($value);
    }
    // --------------------------------------------------------------------------------
    // 全てのエラーメッセージを取得
    // --------------------------------------------------------------------------------
    public function get_error_messages()
    {
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
        $this->fields[$name]->set_error_message($error_message);
    }
    // --------------------------------------------------------------------------------
    // 検証実行
    // --------------------------------------------------------------------------------
    public function validate(...$args)
    {
        $method = '\\core\\validation\\'.$args[0];
        //
        if ($method::run($this, array_slice($args, 1)) == false)
        {
            $this->is_valid = false;
        }
        //
        return $this->is_valid;
    }
}

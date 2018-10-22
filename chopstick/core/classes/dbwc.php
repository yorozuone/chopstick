<?php
namespace core;

class dbwc
{
    private $where_conditions = array();
    private $parameters = array();
    //
    function append_where_condition($value)
    {
        debug::trace('[core/dbwc/append_where_condition] : 開始');
        $this->where_conditions[] = $value;
    }
    //
    function append_parameter($key, $value)
    {
        debug::trace('[core/dbwc/append_parameter] : 開始');
        $this->parameters[$key] = $value;
    }
    //
    function get_where_condition()
    {
        debug::trace('[core/dbwc/get_where_condition] : 開始');
        return implode(' AND ', $this->where_conditions);
    }
    //
    function get_parameters()
    {
        debug::trace('[core/dbwc/get_parameters] : 開始');
        return $this->parameters;
    }
}
<?php
namespace core;

class dbwc
{
    private $where_conditions = array();
    private $parameters = array();
    //
    function append_where_condition($value)
    {
        $this->where_conditions[] = $value;
    }
    //
    function append_parameter($key, $value)
    {
        $this->parameters[$key] = $value;
    }
    //
    function get_where_condition()
    {
        return implode(' AND ', $this->where_conditions);
    }
    //
    function get_parameters()
    {
        return $this->parameters;
    }
}
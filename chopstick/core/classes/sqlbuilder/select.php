<?php
namespace core\sqlbuilder;

class select
{
    private $table  = '';
    private $fields = array();
    private $where  = array();
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function from($table)
    {
        $this->table = $table;
        return $this;
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function select($fields)
    {
        $fields = is_array($fields) ? $fields : array($fields);
        $this->fields = array_merge($this->fields, $fields);
        return $this;
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function where($field, $expr, $value)
    {
        $this->where[] = array('field'=>$field, 'expr'=>$expr, 'value'=>$value);
        return $this;
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function build()
    {
        $this->sql = 'SELECT '.implode(',', $this->fields).' FROM '.$this->table;
        if (count($this->where) > 0)
        {
            $i=0;
            foreach($this->where as $v)
            {
                $i++;
                $this->params[':item'.$i] = $v['value'];
                $w[] = $v['field'].$v['expr'].':item'.$i;
            }
            $this->sql .= ' WHERE '.implode(' AND ', $w);
        }
    }
    //

}
<?php
namespace core;

use \core\config;
use \core\debug;
use \core\globalvars;
use \core\log;

class db
{
    private $_current_connection = null;
    // --------------------------------------------------------------------------------
    // 接続を開く
    // --------------------------------------------------------------------------------
    public function __construct($connection_name = 'default')
    {
        debug::trace('[core/db/__construct] : 開始');
        if ($this->_current_connection == false)
        {
            $config = config::read('db');
            // DB接続
            try
            {
                $this->_current_connection = new \PDO
                (
                    $config[CS_MODE][$connection_name]['dsn'],
                    $config[CS_MODE][$connection_name]['user'],
                    $config[CS_MODE][$connection_name]['passwd'],
                    array
                    (
                        \PDO::ATTR_EMULATE_PREPARES => false
                    )
                );
            }
            catch (PDOException $e)
            {
                debug::alert('[core/db::__construct] '.$e->getMessage());
                return false;
            }
        }
        return true;
    }
    // --------------------------------------------------------------------------------
    // クエリーを発行
    // --------------------------------------------------------------------------------
    public function query($sql, $param = array())
    {
        debug::trace('[core/db/query] : 開始');
        $log = $sql;
        $log = str_replace("\r", ' ', $log);
        $log = str_replace("\n", ' ', $log);
        $log = preg_replace('/[ ]+/', ' ', $log);
        log::write($log, 'notice');
        //
        $log = print_r($param, true);
        $log = str_replace("\r", ' ', $log);
        $log = str_replace("\n", ' ', $log);
        $log = preg_replace('/[ ]+/', ' ', $log);
        log::write($log, 'notice');
        //
        try
        {
            $sth = $this->_current_connection->prepare($sql);
            if ($sth === false) {
                debug::alert('[core/db/query] '.$sql.' / '.print_r($this->_current_connection->errorInfo(), true));
            }
            foreach($param as $k => $v) {
                $sth->bindValue($k, $v);
            }
            $sth->execute();
            return $sth->fetchAll(\PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            log::write('[core/db/query] '.$e->getMessage());
            return false;
        }
    }
 }
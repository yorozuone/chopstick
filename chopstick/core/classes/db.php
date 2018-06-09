<?php
namespace core;

use \core\config;
use \core\debug;
use \core\globalvars;
use \core\log;

class db
{
    const GLOBALVARS_NAME = 'CORE_DB_CONNECTION';
    //
    private $_current_connection = null;
    //
    // --------------------------------------------------------------------------------
    // 接続を開く
    // --------------------------------------------------------------------------------
    //
    public function __construct($connection_name = 'default')
    {
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
    //
    // --------------------------------------------------------------------------------
    // クエリーを発行
    // --------------------------------------------------------------------------------
    //
    public function query($sql, $param = array())
    {
        log::write($sql, 'notice');
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
            debug::alert('[core/db/query] '.$e->getMessage());
            return false;
        }
    }
 }
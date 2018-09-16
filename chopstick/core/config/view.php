<?php
return array
(
    'paths'     => array
                    (
                        CS_BASE_DIR.'app/views/',
                    ),
    'cache'     => CS_MODE == 'production' ? CS_BASE_DIR.'cache/' : false,
    'debug'     => CS_MODE == 'production' ? false : true,
    'function'  => array
        (
            'csrf_token' => array
                (
                    function()
                    {
                        return \core\csrf::create_token();
                    },
                    array(),
                ),
            'has_access' => array
                (
                    function($roll_key)
                    {
                        return \core\acl::has_access($roll_key);
                    }
                ),
        ),
    'filter'    => array(),
);
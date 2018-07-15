<?php
return array
(
    'root'      => 'page/index',
    '404'       => 'page/index',
    'replace'   => array
        (
            '/^login$/' => 'admin/auth/login',
        ),
);
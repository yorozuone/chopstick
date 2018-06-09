<?php
return array
(
    'root'          => 'cms_page/index',
    '404'           => 'cms_page/index',
    'preg_replace'  => array
        (
            '/^login$/' => 'admin/auth/login',
        ),
);
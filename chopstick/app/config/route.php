<?php
return array
(
    'root'      => 'cscms/page',
    '404'       => 'cscms/page',
    'replace'   => array
        (
            '/^login$/' => 'csroot/admin/auth/login',
        ),
);
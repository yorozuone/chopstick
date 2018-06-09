<?php
return array
(
    'function'  => array
    (
        'cs_url' => array
            (
                function($url, $params=array())
                {
                    echo(\app\twig\ext_function\classes\cs_url::create($url, $params));
                },
            ),
        'cs_urlp' => array
            (
                function($url, $params=array())
                {
                    echo(\app\twig\ext_function\classes\cs_url::create_by_page_id($url, $params));
                },
            ),
        'cs_auth_check' => array
            (
                function()
                {
                    return \core\auth::check();
                },
            ),
        'cs_stack' => array
            (
                function($params=array())
                {
                    echo(\app\twig\ext_function\classes\cs_stack::render($params));
                },
                array
                (
                    'is_safe' => array('html'),
                ),
            ),
        'cs_pagelist' => array
            (
                function($params=array())
                {
                    echo(\app\twig\ext_function\classes\cs_pagelist::render($params));
                },
            ),
        'cs_pagenavi' => array
            (
                function($params=array())
                {
                    echo(\app\twig\ext_function\classes\cs_pagenavi::render($params));
                },
            ),
        'cs_breadcrumb' => array
            (
                function($params=array())
                {
                    echo(\app\twig\ext_function\classes\cs_breadcrumb::render($params));
                },
            ),
        'cs_rss' => array
            (
                function($params=array())
                {
                    echo(\app\twig\ext_function\classes\cs_rss::render($params));
                },
            ),
        'cs_title' => array
            (
                function($params=array())
                {
                    echo(\app\twig\ext_function\classes\cs_title::render($params));      
                },
            ),
        'cs_news'       => array
            (
                function($params=array())
                {
                    echo(\app\twig\ext_function\classes\cs_news::render($params));
                },
            ),
    ),
    'filter'    => array
    (
        'cs_markdown' => array
        (
            function($text='')
            {
                return \app\twig\ext_filter\classes\cs_markdown::render($text);
            },
            array
            (
                'is_safe' => array('html'),
            ),
        )
    ),
);
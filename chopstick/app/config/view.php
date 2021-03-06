<?php
return array
(
    'function'  => array
    (
        'cs_urlp' => array
            (
                function($url, $params=array())
                {
                    echo(\app\cscms\twig\ext_function\cs_url\controller::create_by_page_id($url, $params));
                },
            ),
        'cs_stack' => array
            (
                function($params=array())
                {
                    echo(\app\cscms\twig\ext_function\cs_stack\controller::display($params));
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
                    echo(\app\cscms\twig\ext_function\cs_pagelist\controller::display($params));
                },
            ),
        'cs_pagenavi' => array
            (
                function($params=array())
                {
                    echo(\app\cscms\twig\ext_function\cs_pagenavi\controller::display($params));
                },
            ),
        'cs_breadcrumb' => array
            (
                function($params=array())
                {
                    echo(\app\cscms\twig\ext_function\cs_breadcrumb\controller::display($params));
                },
            ),
        'cs_news'       => array
            (
                function($params=array())
                {
                    echo(\app\cscms\twig\ext_function\cs_news\controller::display($params));
                },
            ),
        'cs_rss' => array
            (
                function($params=array())
                {
                    echo(\app\cscms\twig\ext_function\cs_rss\controller::display($params));
                },
            ),
        'cs_title' => array
            (
                function($params=array())
                {
                    echo(\app\cscms\twig\ext_function\cs_title\controller::display($params));
                },
            ),
        'cs_url' => array
            (
                function($url, $params=array())
                {
                    echo(\app\cscms\twig\ext_function\cs_url\controller::create($url, $params));
                },
            ),
    ),
    'filter'    => array
    (
        'cs_markdown' => array
        (
            function($text='')
            {
                return \app\cscms\twig\ext_filter\cs_markdown\controller::display($text);
            },
            array
            (
                'is_safe' => array('html'),
            ),
        )
    ),
);
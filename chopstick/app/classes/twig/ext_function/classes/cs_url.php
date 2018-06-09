<?php
namespace app\twig\ext_function\classes;

use \core\url;

use \app\model\helper\page as dset_page;

class cs_url
{
    // --------------------------------------------------------------------------------
    // URL を作成する
    // --------------------------------------------------------------------------------
    public static function create($url, $params = array())
    {
        return url::create($url, $params);
    }
    // --------------------------------------------------------------------------------
    // page_id から、URL を作成する
    // --------------------------------------------------------------------------------
    public static function create_by_page_id($page_id, $params = array())
    {
        $dset_page = new dset_page();
        return url::create($dset_page->get_permanent_name_from_page_id($page_id), $params);
    }
}

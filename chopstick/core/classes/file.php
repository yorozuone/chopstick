<?php
namespace core;

class file
{
    const TYPE_FILE     = 1;
    const TYPE_FOLDER   = 2;
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function path_join(string ...$paths)
    {
        $path = '';
        if (count($paths) == 0)
        {
            return $path;
        }
        $path = $paths[0];
        for($i=1; $i<count($paths); $i++)
        {
            $path_1 = rtrim($path, DIRECTORY_SEPARATOR);
            $path_2 = ltrim($paths[$i], DIRECTORY_SEPARATOR);
            $path = $path_1.DIRECTORY_SEPARATOR.$path_2;
        }
        return $path;
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function get_file_path($target_path, $scandir = array('site', 'app', 'core'))
    {
        foreach($scandir as $v_folder)
        {
            $file_name = self::path_join(CS_BASE_DIR, $v_folder, $target_path);
            if (is_readable($file_name))
            {
                return $file_name;
            }
        }
        return false;
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function get_file_paths($target_path, $scandir = array('site', 'app', 'core'))
    {
        $paths = array();
        //
        foreach($scandir as $v_folder)
        {
            $file_name = self::path_join(CS_BASE_DIR, $v_folder, $target_path);
            if (is_readable($file_name))
            {
                $paths[] = $file_name;
            }
        }
        return $paths;
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function get_list($target_path, $section = array('site', 'app', 'core'), $type=self::TYPE_FILE)
    {
        $paths = array();
        //
        foreach($section as $p_path_1)
        {
            $p_path_2 = self::path_join(CS_BASE_DIR, $p_path_1, $target_path);
            //
            if (is_readable($p_path_2))
            {
                $p_path_3 = scandir($p_path_2);
                //
                foreach($p_path_3 as $p_path_4)
                {
                    $p_path_5 = file::path_join($p_path_2, $p_path_4);
                    //
                    $pi = pathinfo($p_path_5);
                    //
                    $is_dir = is_dir($p_path_5); 
                    //
                    if ($is_dir and ($pi['basename'] == '.' or $pi['basename'] == '..'))
                    {
                        continue;
                    }
                    //
                    $obj = array();
                    if ($is_dir)
                    {
                        $obj['type'] = 'folder';
                    }
                    else
                    {
                        $obj['type'] = 'file';
                    }
                    $obj['section']     = $p_path_1;
                    $obj['path']        = $p_path_5;
                    $obj['basename']    = $pi['basename'];
                    $obj['filename'] = isset($pi['filename']) ? $pi['filename'] : '';
                    $obj['extension'] = isset($pi['extension']) ? $pi['extension'] : '';
                    //
                    if
                    (
                        (
                            $obj['type'] == 'file' AND
                            ($type & self::TYPE_FILE) == self::TYPE_FILE
                        )
                        OR
                        (
                            $obj['type'] == 'folder' and
                            ($type & self::TYPE_FOLDER) == self::TYPE_FOLDER
                        )
                    )
                    {

                        $paths[] = $obj;
                    }
                }
            }
        }
        return $paths;
    }
}

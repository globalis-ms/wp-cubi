<?php
namespace App\Test\Page;

class BackOffice
{
    // include url of current page
    public static $URL = '/wp/wp-admin/';

    public static function route($param)
    {
        return static::$URL.$param;
    }
}

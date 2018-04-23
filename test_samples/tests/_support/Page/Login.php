<?php
namespace WpCubiTest\Page;

class Login
{
    // include url of current page
    public static $URL = '/wp/wp-login.php';

    public static $LoginForm = '#loginform';

    public static function route($param)
    {
        return static::$URL.$param;
    }
}

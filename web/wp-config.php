<?php

use Globalis\WP\LocalConfig\Vars;

require_once dirname(__DIR__) . '/vendor/autoload.php';

Vars::init(dirname(__DIR__) . '/config/vars.php');

require_once dirname(__DIR__) . '/config/application.php';

require_once ABSPATH . 'wp-settings.php';

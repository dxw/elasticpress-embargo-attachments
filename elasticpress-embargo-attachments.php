<?php

if (!defined('ABSPATH')) {
    die('-1');
}

/*
Plugin Name: ElasticPress Embargo Attachments
Plugin URI: http://dxw.com
Description: Allows embargo times to be set on media library documents, and prevents them appearing in search until the embargo has expired.
Version: 1.0
Author: dxw
Author URI: http://dxw.com
*/

//autoloads classes, no other setup required
$registrar = require __DIR__.'/src/load.php';

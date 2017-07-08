<?php
declare(strict_types = 1);

define('CONV_ROOT_DIR', __DIR__ . '/..');
define('CONV_CLASS_DIR', __DIR__ . '/../src/');
define('VENDOR_DIR', __DIR__ . '/../vendor/');

if (version_compare(PHP_VERSION, '5.3.19', ">=") && file_exists(VENDOR_DIR . '/autoload.php')) {
    require_once VENDOR_DIR . '/autoload.php';
}

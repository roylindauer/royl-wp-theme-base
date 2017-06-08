<?php
if (file_exists( __DIR__ . '/../vendor/autoload.php')) {
    include_once __DIR__ . '/../vendor/autoload.php';
}

spl_autoload_register(function ($class) {

    $prefix = 'Royl\\WpThemeBase\\';
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);

    $located = locate_template('src/' . str_replace('\\', '/', $relative_class) . '.php', TRUE);

    return $located ? true : false;
});
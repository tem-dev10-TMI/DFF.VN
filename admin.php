<?php
// autoload
spl_autoload_register(function($class){
    $paths = [
        __DIR__ . '/model/admin' . $class . '.php',
        __DIR__ . '/controller/admin' . $class . '.php'
    ];
    foreach ($paths as $p) if (file_exists($p)) require_once $p;
});

require_once __DIR__ . '/routes.php';
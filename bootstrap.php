<?php

spl_autoload_register('app_autoloader');

function app_autoloader($className) {
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    $filePath = __DIR__ . DIRECTORY_SEPARATOR . $className . '.php';

    if (file_exists($filePath)) {
        require_once $filePath;
    } else {
    
        echo "Class file not found: $filePath";
    }
}

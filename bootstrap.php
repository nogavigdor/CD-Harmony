<?php

spl_autoload_register('app_autoloader');

function app_autoloader($className) {
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    $filePath = __DIR__ . DIRECTORY_SEPARATOR . $className . '.php';

    if (file_exists($filePath)) {
        require_once $filePath;
    } else {
        // Improve error reporting
        trigger_error("Class file not found: $filePath", E_USER_ERROR);
    }
}

<?php

function app_autoload($className) {
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    $filePath = __DIR__ . DIRECTORY_SEPARATOR . $className . '.php';

    if (file_exists($filePath)) {
        include $filePath;
    }
}

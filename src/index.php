<?php

session_start();
if (!isset($_SESSION['signed'])) {
    $_SESSION['signed'] = 0;
}

function load($path)
{
    if (file_exists($path)) {
        require_once $path;
    }
}

function controllersLoader($className)
{
    $filePath = 'app/controllers/' . $className . '.php';
    load($filePath);
}

function modelsLoader($className)
{
    $filePath = 'app/models/' . $className . '.php';
    load($filePath);
}

function viewsLoader($className)
{
    $filePath = 'app/views/' . $className . '.php';
    load($filePath);
}

spl_autoload_register('controllersLoader');
spl_autoload_register('modelsLoader');
spl_autoload_register('viewsLoader');
spl_autoload_register(function ($className) {
    $filePath = 'app/core/' . $className . '.php';
    load($filePath);
});

Route::init();
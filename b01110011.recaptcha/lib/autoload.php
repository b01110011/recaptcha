<?php

/**
 * Автозагрузчик файлов для классов.
 * 
 * - папка соответствует пространству имён
 * - название файла и название класса идентичны
 * - всё регистрозависимо
 * 
 * Файловая система:
 * B01110011ReCaptcha\
 *      ReCaptcha.php
 * autoload.php
 */
spl_autoload_register(function($class)
{
    $namespace = 'B01110011ReCaptcha\\';

    if (substr($class, 0, strlen($namespace)) !== $namespace) return;

    $class = str_replace('\\', '/', $class);

    $path = __DIR__ .'/'. $class .'.php';
    if (is_readable($path)) require_once $path;
});
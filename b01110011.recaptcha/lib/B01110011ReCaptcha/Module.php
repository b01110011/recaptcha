<?php

namespace B01110011ReCaptcha;

abstract class Module
{
    static $id = '';
    static $idPrefix = '';
    static $locPrefix = '';
    static $filePrefix = '';

    /**
     * Получаем айди модуля.
     */
    static public function id()
    {
        if (empty(self::$id))
            self::$id = basename(dirname(dirname(__DIR__)));

        return self::$id;
    }

    /**
     * Получаем префикс айди модуля, используется для получения префикса для ключей локализации или файлов для копирования в админ часть.
     */
    static public function idPrefix()
    {
        if (empty(self::$idPrefix))
            self::$idPrefix = str_replace('.', '_', self::id());

        return self::$idPrefix;
    }

    /**
     * Получаем префикс для ключей локализации.
     */
    static public function locPrefix()
    {
        if (empty(self::$locPrefix))
            self::$locPrefix = strtoupper(self::idPrefix()) .'_';

        return self::$locPrefix;
    }

    /**
     * Получаем префикс файлов для копирования в админ часть.
     */
    static public function filePrefix()
    {
        if (empty(self::$filePrefix))
            self::$filePrefix = self::idPrefix() .'_';

        return self::$filePrefix;
    }
}
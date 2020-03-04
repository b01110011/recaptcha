<?php

use Bitrix\Main\EventManager;

$module_id = 'b01110011.recaptcha';
$EventManager = EventManager::getInstance();

$handlers = $EventManager->findEventHandlers('main', 'OnBeforeProlog', [$module_id]);
if (!empty($handlers))
{
    $EventManager->unRegisterEventHandler('main', 'OnBeforeProlog', $module_id, 'B01110011ReCaptcha\BitrixCaptcha', 'initCheckSpam');

    $EventManager->registerEventHandler('main', 'OnPageStart', $module_id, 'B01110011ReCaptcha\BitrixCaptcha', 'initCheckSpam');
}
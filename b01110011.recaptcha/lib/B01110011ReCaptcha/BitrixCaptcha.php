<?php

namespace B01110011ReCaptcha;

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

use B01110011ReCaptcha\Module as M;

Loc::loadMessages(__FILE__);

class BitrixCaptcha
{
    /**
     * Подключаем JS скрипты для reCaptcha v3
     */
    public function initJS()
    {
        $Asset = Asset::getInstance();
        $siteKey = Option::get(M::id(), 'site_key');
        $hideBadge = Option::get(M::id(), 'hide_badge', 'Y');

        $Asset->addString('<script src="https://www.google.com/recaptcha/api.js?render='. $siteKey .'"></script>');
        $Asset->addString('<script>window.recaptcha = { siteKey: "'. $siteKey .'", tokenLifeTime: 100 };</script>'); // время жизни токена в секундах (2 минуты максимальное время жизни токена)
        $Asset->addString('<script src="/bitrix/js/b01110011.recaptcha/script.js"></script>');
        
        if ($hideBadge == 'Y')
            $Asset->addString('<style>.grecaptcha-badge {display: none;}</style>');
    }

    /**
     * Подключаем проверку на спам
     */
    public function initCheckSpam()
    {
        $EventManager = EventManager::getInstance();

        $EventManager->addEventHandler('form', 'onBeforeResultAdd', ['B01110011ReCaptcha\BitrixCaptcha', 'checkWebForm']);
        $EventManager->addEventHandler('main', 'OnBeforeUserRegister', ['B01110011ReCaptcha\BitrixCaptcha', 'checkRegistration']);
        $EventManager->addEventHandler('iblock', 'OnBeforeIBlockElementAdd', ['B01110011ReCaptcha\BitrixCaptcha', 'checkIBlock']);
    }

    /**
     * Проверка форм из модуля веб форм
     */
    public function checkWebForm($WEB_FORM_ID, &$arFields, &$arValues)
    {
        $webformIDs = Option::get(M::id(), 'webform_ids');
        if (empty($webformIDs)) return true;

        // если не из списка проверяемых форм пришли данные, то не проверяем капчу
        $webformIDs = explode(',', $webformIDs);
        if (!in_array($WEB_FORM_ID, $webformIDs, true)) return true;
        
        return self::checkSpam();
    }

    /**
     * Проверка при регистрации пользователя
     */
    public function checkRegistration(&$arArgs)
    {
        $registrationEnable = Option::get(M::id(), 'registrationEnable', 'N');
        if ($registrationEnable == 'N') return true;

        return self::checkSpam();
    }

    /**
     * Проверка при добавлении в инфоблок
     */
    public function checkIBlock(&$arParams)
    {
        $iblockIDs = Option::get(M::id(), 'iblock_ids');
        if (empty($iblockIDs)) return true;

        // если не из списка проверяемых инфоблоков пришли данные, то не проверяем капчу
        $iblockIDs = explode(',', $iblockIDs);
        if (!in_array((string) $arParams['IBLOCK_ID'], $iblockIDs, true)) return true;
        
        return self::checkSpam();
    }

    /**
     * Основной метод проверки капчи
     */
    public function checkSpam()
    {
        global $APPLICATION;

        // если мы добавляем данные из админки, то не проверяем
        if (preg_match('/^\/bitrix\/.*$/i', $APPLICATION->GetCurPage())) return true;

        $isError = false;
        $recaptcha_token = $_REQUEST['recaptcha_token'];

        if (isset($recaptcha_token) && !empty($recaptcha_token))
        {
            $secretKey = Option::get(M::id(), 'secret_key');
            $permissibleScore = (float) Option::get(M::id(), 'permissible_score', 0.5);

            $recaptcha = new ReCaptcha($secretKey, $permissibleScore);
            $response = $recaptcha->verify($recaptcha_token);

            if (!$response['isSuccess']) $isError = true;
        }
        else
        {
            $isError = true;
        }

        if ($isError)
        {
            $errorMessage = Option::get(M::id(), 'error_message');
            if (empty($errorMessage)) $errorMessage = Loc::getMessage(M::locPrefix() .'CAPTCHA_ERROR_MESSAGE');

            $APPLICATION->ThrowException($errorMessage);
            return false;
        }
    }
}
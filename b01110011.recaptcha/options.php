<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;

use B01110011ReCaptcha\Module as M;

$module_id = M::id();

Loc::loadMessages($_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/modules/main/options.php');
Loc::loadMessages(__FILE__);

// проверка прав на настройки модуля
if ($APPLICATION->GetGroupRight($module_id) < 'S')
{
    $APPLICATION->AuthForm(Loc::getMessage('ACCESS_DENIED'));
}

Loader::includeModule($module_id);

$request = HttpApplication::getInstance()->getContext()->getRequest();

// формируем вкладки и поля форм
$aTabs = [];
$rsSites = CSite::GetList($by = 'sort', $order = 'asc', ['ACTIVE' => 'Y']);
while ($arSite = $rsSites->Fetch())
{
    $arOptions = [];

    // Основные настройки
    $arOptions = array_merge($arOptions,
    [
        Loc::getMessage(M::locPrefix() .'HEADER_BASE_SETTINGS'),
        [
            'site_key_'. $arSite['LID'],
            Loc::getMessage(M::locPrefix() .'FIELD_SITE_KEY'),
            '',
            ['text', 50]
        ],
        [
            'secret_key_'. $arSite['LID'],
            Loc::getMessage(M::locPrefix() .'FIELD_SECRET_KEY'),
            '',
            ['text', 50]
        ],
        [
            'permissible_score_'. $arSite['LID'],
            Loc::getMessage(M::locPrefix() .'FIELD_PERMISSIBLE_SCORE'),
            '0.5',
            ['text', 5]
        ],
        [
            'hide_badge_'. $arSite['LID'],
            Loc::getMessage(M::locPrefix() .'FIELD_HIDE_BADGE'),
            'Y',
            ['checkbox']
        ],
        [
            'error_message_'. $arSite['LID'],
            Loc::getMessage(M::locPrefix() .'FIELD_ERROR_MESSAGE'),
            '',
            ['text', 50]
        ]
    ]);


    // Регистрация пользователей
    $arOptions = array_merge($arOptions,
    [
        Loc::getMessage(M::locPrefix() .'HEADER_REGISTRATION'),
        [
            'registration_enable_'. $arSite['LID'],
            Loc::getMessage(M::locPrefix() .'FIELD_REGISTRATION'),
            'N',
            ['checkbox']
        ]
    ]);


    // Веб Формы
    if (Loader::includeModule('form'))
    {
        // получаем список форм
        $arWebForm = [];
        $rsForms = CForm::GetList($by2 = 's_sort', $order2 = 'asc', ['SITE' => $arSite['LID']], $filtered);
        while ($arForm = $rsForms->Fetch())
        {
            $arWebForm[$arForm['ID']] = '[' . $arForm['ID'] . '] ' . $arForm['NAME'];
        }

        $arOptions = array_merge($arOptions,
        [
            Loc::getMessage(M::locPrefix() .'HEADER_WEBFORM_IDS'),
            [
                'webform_ids_'. $arSite['LID'],
                Loc::getMessage(M::locPrefix() .'FIELD_WEBFORM_IDS'),
                '',
                ['multiselectbox', $arWebForm]
            ]
        ]);
    }


    // Инфоблоки
    if (Loader::includeModule('iblock'))
    {
        // получаем список форм
        $arBlocks = [];
        $rsBlocks = CIBlock::GetList(['SORT' => 'ASC'], ['SITE_ID' => $arSite['LID']]);
        while ($arBlock = $rsBlocks->Fetch())
        {
            $arBlocks[$arBlock['ID']] = '[' . $arBlock['ID'] . '] ' . $arBlock['NAME'];
        }

        $arOptions = array_merge($arOptions,
        [
            Loc::getMessage(M::locPrefix() .'HEADER_IBLOCK'),
            [
                'iblock_ids_'. $arSite['LID'],
                Loc::getMessage(M::locPrefix() .'FIELD_IBLOCK_IDS'),
                '',
                ['multiselectbox', $arBlocks]
            ]
        ]);
    }


    // Форма обратной связи main.feedback
    // получаем список почтовых шаблонов
    $arEvents = [];
    $rsEvent = CEventMessage::GetList($by2 = 'ID', $order2 = 'DESC', ['LID' => $arSite['LID'], "TYPE_ID" => "FEEDBACK_FORM", "ACTIVE" => "Y"]);
    while ($arEvent = $rsEvent->GetNext())
    {
        $arEvents[$arEvent['ID']] = '[' . $arEvent['ID'] . '] ' . $arEvent['SUBJECT'];
    }

    $arOptions = array_merge($arOptions,
    [
        Loc::getMessage(M::locPrefix() .'HEADER_MAIN_FEEDBACK'),
        [
            'main_feedback_ids_'. $arSite['LID'],
            Loc::getMessage(M::locPrefix() .'FIELD_MAIN_FEEDBACK_IDS'),
            '',
            ['multiselectbox', $arEvents]
        ],
        ['note' => Loc::getMessage(M::locPrefix() .'NOTE_MAIN_FEEDBACK')]
    ]);


    // Оформление заказа
    if (Loader::includeModule('sale'))
    {
        $arOptions = array_merge($arOptions,
        [
            Loc::getMessage(M::locPrefix() .'HEADER_SALE_ORDER'),
            [
                'sale_order_enable_'. $arSite['LID'],
                Loc::getMessage(M::locPrefix() .'FIELD_SALE_ORDER'),
                'N',
                ['checkbox']
            ],
            ['note' => Loc::getMessage(M::locPrefix() .'NOTE_SALE_ORDER')]
        ]);
    }


    // список сайтов
    $aTabs[] =
    [
        'DIV' => 'settings_'. $arSite['LID'],
        'TAB' => $arSite['NAME'],
        'OPTIONS' => $arOptions
    ];
}


// сохранение настроек
if ($request->isPost() && $request['Update'] && check_bitrix_sessid())
{
    foreach ($aTabs as $aTab)
    {
        foreach ($aTab['OPTIONS'] as $arOption)
        {
            if (!is_array($arOption)) continue; // строка с подсветкой, используется для разделения настроек в одной вкладке
            if ($arOption['note']) continue; // уведомление с подсветкой

            __AdmSettingsSaveOption($module_id, $arOption);
        }
    }
}

// вывод формы
$tabControl = new CAdminTabControl('tabControl', $aTabs);
?>

<? $tabControl->Begin(); ?>
<form method="POST"
    action="<?=$APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($request['mid'])?>&lang=<?=$request['lang']?>"
    name="<?=M::idPrefix() . '_settings'?>">

    <?
    foreach ($aTabs as $aTab)
    {
        if ($aTab['OPTIONS'])
        {
            $tabControl->BeginNextTab();
            __AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
        }
    }
    ?>

    <? $tabControl->Buttons(); ?>
    <input type="submit" name="Update" value="<?=Loc::getMessage('MAIN_SAVE')?>">
    <input type="reset" name="reset" value="<?=Loc::getMessage('MAIN_RESET')?>">

    <?=bitrix_sessid_post()?>
</form>
<? $tabControl->End(); ?>
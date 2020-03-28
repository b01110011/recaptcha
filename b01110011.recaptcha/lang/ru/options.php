<?php

use B01110011ReCaptcha\Module as M;

$MESS[M::locPrefix() .'HEADER_BASE_SETTINGS'] = 'Основные настройки';
$MESS[M::locPrefix() .'HEADER_REGISTRATION'] = 'Регистрация';
$MESS[M::locPrefix() .'HEADER_WEBFORM_IDS'] = 'Веб Формы';
$MESS[M::locPrefix() .'HEADER_IBLOCK'] = 'Инфоблоки';
$MESS[M::locPrefix() .'HEADER_MAIN_FEEDBACK'] = 'Форма обратной связи (main.feedback)';
$MESS[M::locPrefix() .'HEADER_SALE_ORDER'] = 'Оформление заказа (sale.order.ajax)';

$MESS[M::locPrefix() .'NOTE_MAIN_FEEDBACK'] = 'Сообщение об ошибке капчи в этом компоненте не выводится, связано это с устройством компонента. Если кастомизировать компонент, то ошибка капчи появится. Инструкция на странице "Установки" модуля.';
$MESS[M::locPrefix() .'NOTE_SALE_ORDER'] = 'Событие OnBeforeOrderAdd (которое тут используется) устарело с версии 15.5.0, но в продукте сохранена обратная совместимость. Поэтому его можно использовать, если в настройках модуля Интернет-магазин отмечена опция Включить обработку устаревших событий.';

$MESS[M::locPrefix() .'FIELD_SITE_KEY'] = 'Ключ сайта';
$MESS[M::locPrefix() .'FIELD_SECRET_KEY'] = 'Секретный ключ';
$MESS[M::locPrefix() .'FIELD_PERMISSIBLE_SCORE'] = 'Допустимая оценка';
$MESS[M::locPrefix() .'FIELD_HIDE_BADGE'] = 'Скрыть значок';
$MESS[M::locPrefix() .'FIELD_WEBFORM_IDS'] = 'ID веб формы';
$MESS[M::locPrefix() .'FIELD_REGISTRATION'] = 'Включить капчу';
$MESS[M::locPrefix() .'FIELD_ERROR_MESSAGE'] = 'Сообщение об ошибке капчи';
$MESS[M::locPrefix() .'FIELD_IBLOCK_IDS'] = 'ID инфоблока';
$MESS[M::locPrefix() .'FIELD_MAIN_FEEDBACK_IDS'] = 'ID почтовых шаблонов';
$MESS[M::locPrefix() .'FIELD_SALE_ORDER'] = 'Включить капчу';
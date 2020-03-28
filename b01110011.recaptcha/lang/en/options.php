<?php

use B01110011ReCaptcha\Module as M;

$MESS[M::locPrefix() .'HEADER_BASE_SETTINGS'] = 'Base settings';
$MESS[M::locPrefix() .'HEADER_REGISTRATION'] = 'Registration';
$MESS[M::locPrefix() .'HEADER_WEBFORM_IDS'] = 'Web Form';
$MESS[M::locPrefix() .'HEADER_IBLOCK'] = 'Info Blocks';
$MESS[M::locPrefix() .'HEADER_MAIN_FEEDBACK'] = 'Feedback form (main.feedback)';
$MESS[M::locPrefix() .'HEADER_SALE_ORDER'] = 'Make order (sale.order.ajax)';

$MESS[M::locPrefix() .'NOTE_MAIN_FEEDBACK'] = 'A captcha error message is not displayed in this component, because this is how the component works. If you customize the component, a captcha error will appear. Instructions on the "Settings" page of the module.';
$MESS[M::locPrefix() .'NOTE_SALE_ORDER'] = 'The OnBeforeOrderAdd event (which is used here) has been deprecated since version 15.5.0, but backward compatibility is preserved in the product. Therefore, it can be used if the Enable processing of obsolete events option is checked in the settings of the Online Store module.';

$MESS[M::locPrefix() .'FIELD_SITE_KEY'] = 'Site key';
$MESS[M::locPrefix() .'FIELD_SECRET_KEY'] = 'Secret key';
$MESS[M::locPrefix() .'FIELD_PERMISSIBLE_SCORE'] = 'Permissible score';
$MESS[M::locPrefix() .'FIELD_HIDE_BADGE'] = 'Hide badge';
$MESS[M::locPrefix() .'FIELD_WEBFORM_IDS'] = 'Web Form ID';
$MESS[M::locPrefix() .'FIELD_REGISTRATION'] = 'Enable captcha';
$MESS[M::locPrefix() .'FIELD_ERROR_MESSAGE'] = 'Captcha error message';
$MESS[M::locPrefix() .'FIELD_IBLOCK_IDS'] = 'IBlock ID';
$MESS[M::locPrefix() .'FIELD_MAIN_FEEDBACK_IDS'] = 'Mail template ID';
$MESS[M::locPrefix() .'FIELD_SALE_ORDER'] = 'Enable captcha';
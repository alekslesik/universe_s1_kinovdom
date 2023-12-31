<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'CONSENT_URL' => null
], $arParams);

$arResult['CONSENT'] = [
    'SHOW' => false,
    'URL' => null
];

if (!Loader::includeModule('intec.core'))
    return;

$arResult['CONSENT'] = [
    'SHOW' => !defined('EDITOR') ? Properties::get('base-consent') : null,
    'URL' => $arParams['CONSENT_URL']
];

if (!empty($arResult['CONSENT']['URL'])) {
    $arResult['CONSENT']['URL'] = StringHelper::replaceMacros($arResult['CONSENT']['URL'], [
        'SITE_DIR' => SITE_DIR
    ]);
} else {
    $arResult['CONSENT']['SHOW'] = false;
}
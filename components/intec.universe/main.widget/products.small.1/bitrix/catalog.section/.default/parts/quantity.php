<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arVisual
 */

$vQuantity = function (&$arItem) use (&$arVisual, &$arParams, &$APPLICATION, &$component) {
    $fRender = function (&$arItem, $bOffer = false) use (&$arVisual, &$arParams, &$APPLICATION, &$component) { ?>
        <?php if ($arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS']) && !$bOffer) return ?>
        <?= Html::beginTag('div', [
            'class' => 'widget-item-quantity',
            'data-offer' => $bOffer ? $arItem['ID'] : 'false',
            'data-role' => 'item.quantity'
        ]) ?>
            <div class="intec-grid intec-grid-nowrap intec-grid-a-v-center intec-grid-i-h-4">
                <?php if ($arItem['CAN_BUY']) { ?>
                    <?php if ($arVisual['QUANTITY']['MODE'] !== 'text') { ?>
                        <div class="intec-grid-item-auto">
                            <div class="widget-item-quantity-icon" data-quantity-state="many"></div>
                        </div>
                        <div class="intec-grid-item">
                            <div class="widget-item-quantity-value" data-quantity-state="many">
                            <?php $iOffset = StringHelper::position('.', $arItem['CATALOG_QUANTITY']);

                                $iPrecision = 0;

                                if ($iOffset)
                                    $iPrecision = StringHelper::length(
                                        StringHelper::cut($arItem['CATALOG_QUANTITY'], $iOffset + 1)
                                    );

                                $arItem['CATALOG_QUANTITY'] = number_format(
                                    $arItem['CATALOG_QUANTITY'],
                                    $iPrecision,
                                    '.',
                                    ' '
                                );

                                unset($iOffset, $iPrecision);

                            ?>
                                <?php if ($arVisual['QUANTITY']['MODE'] === 'number' && $arItem['CATALOG_QUANTITY'] > 0) { ?>
                                    <span data-role="stores.popup.button">
                                        <?= Loc::getMessage('C_WIDGET_PRODUCTS_SMALL_1_QUANTITY_AVAILABLE') ?>
                                    </span>
                                    <span class="widget-item-quantity-value-numeric">
                                        <?= Loc::getMessage('C_WIDGET_PRODUCTS_SMALL_1_QUANTITY_VALUE_MEASURE', [
                                            '#VALUE#' => $arItem['CATALOG_QUANTITY'],
                                            '#MEASURE#' => !empty($arItem['CATALOG_MEASURE_NAME']) ? ' '.$arItem['CATALOG_MEASURE_NAME']: null
                                        ]) ?>
                                    </span>
                                <?php } else { ?>
                                    <span data-role="stores.popup.button">
                                        <?= Loc::getMessage('C_WIDGET_PRODUCTS_SMALL_1_QUANTITY_AVAILABLE') ?>
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } else {
                        $sState = 'empty';

                        if ($arItem['CATALOG_QUANTITY'] >= $arVisual['QUANTITY']['BOUNDS']['MANY'])
                            $sState = 'many';
                        else if ($arItem['CATALOG_QUANTITY'] < $arVisual['QUANTITY']['BOUNDS']['MANY'] && $arItem['CATALOG_QUANTITY'] > $arVisual['QUANTITY']['BOUNDS']['FEW'])
                            $sState = 'enough';
                        else if ($arItem['CATALOG_QUANTITY'] <= $arVisual['QUANTITY']['BOUNDS']['FEW'] && $arItem['CATALOG_QUANTITY'] > 0)
                            $sState = 'few';
                        else if ($arItem['CATALOG_QUANTITY_TRACE'] === 'N' || $arItem['CATALOG_CAN_BUY_ZERO'] === 'Y')
                            $sState = 'many'
                    ?>
                        <div class="intec-grid-item-auto">
                            <div class="widget-item-quantity-icon" data-quantity-state="<?= $sState ?>"></div>
                        </div>
                        <div class="intec-grid-item">
                            <div class="widget-item-quantity-value" data-quantity-state="<?= $sState ?>">
                                <?php if ($arVisual['QUANTITY']['MODE'] === 'text') { ?>
                                    <span>
                                        <?php if ($sState === 'many') { ?>
                                            <?= Loc::getMessage('C_WIDGET_PRODUCTS_SMALL_1_QUANTITY_BOUNDS_MANY') ?>
                                        <?php } else if ($sState === 'enough') { ?>
                                            <?= Loc::getMessage('C_WIDGET_PRODUCTS_SMALL_1_QUANTITY_BOUNDS_ENOUGH') ?>
                                        <?php } else if ($sState === 'few') { ?>
                                            <?= Loc::getMessage('C_WIDGET_PRODUCTS_SMALL_1_QUANTITY_BOUNDS_FEW') ?>
                                        <?php } ?>
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="intec-grid-item-auto">
                        <div class="widget-item-quantity-icon" data-quantity-state="empty"></div>
                    </div>
                    <div class="intec-grid-item">
                        <div class="widget-item-quantity-value" data-quantity-state="empty">
                            <span data-role="stores.popup.button">
                                <?= Loc::getMessage('C_WIDGET_PRODUCTS_SMALL_1_QUANTITY_UNAVAILABLE') ?>
                            </span>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?= Html::endTag('div') ?>
    <?php };

    $fRender($arItem, false);

    if (!empty($arItem['OFFERS']))
        foreach ($arItem['OFFERS'] as &$arOffer) {
            $fRender($arOffer, true);

            unset($arOffer);
        }
};
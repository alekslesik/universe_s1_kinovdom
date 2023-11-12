<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arBlocks = $arResult['BLOCKS'];
$arVisual = $arResult['VISUAL'];
$arSvg = [
    'RATING' => FileHelper::getFileData(__DIR__.'/svg/rating.svg')
];

?>
<div class="widget c-reviews c-reviews-template-15" id="<?= $sTemplateId ?>">
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW'] || $arBlocks['FOOTER']['SHOW'] || $arVisual['SEND']['USE']) { ?>
                <div class="widget-header">
                    <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['FOOTER']['SHOW'] || $arVisual['SEND']['USE']) { ?>
                        <?= Html::beginTag('div', [
                            'class' => [
                                'widget-title',
                                'align-'.$arBlocks['HEADER']['POSITION'],
                                    $arBlocks['HEADER']['POSITION'] === 'center' && $arBlocks['FOOTER']['SHOW'] ? 'widget-title-margin' : null
                            ]
                        ]) ?>
                            <div class="intec-grid intec-grid-a-v-center intec-grid-a-h-end intec-grid-i-h-12">
                                <?php if ($arBlocks['HEADER']['SHOW']) { ?>
                                    <div class="intec-grid-item">
                                        <?= $arBlocks['HEADER']['TEXT'] ?>
                                    </div>
                                <?php } ?>
                                <?php if ($arVisual['SEND']['USE']) { ?>
                                    <div class="intec-grid-item-auto">
                                        <?= Html::beginTag('div', [
                                            'class' => [
                                                'widget-send',
                                                'intec-cl' => [
                                                    'text-hover',
                                                    'border-hover',
                                                    'svg-path-stroke-hover'
                                                ]
                                            ],
                                            'data-role' => 'review.send'
                                        ]) ?>
                                            <div class="intec-grid intec-grid-a-v-center intec-grid-i-h-4">
                                                <div class="widget-send-icon intec-ui-picture intec-grid-item-auto">
                                                    <?= FileHelper::getFileData(__DIR__.'/svg/send.svg') ?>
                                                </div>
                                                <div class="widget-send-content intec-grid-item">
                                                    <?= Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_15_TEMPLATE_SEND_BUTTON_DEFAULT') ?>
                                                </div>
                                            </div>
                                        <?= Html::endTag('div') ?>
                                    </div>
                                <?php } ?>
                                <?php if ($arBlocks['FOOTER']['SHOW']) { ?>
                                    <div class="intec-grid-item-auto">
                                        <?= Html::beginTag('a', [
                                            'class' => 'widget-all',
                                            'href' => $arBlocks['FOOTER']['LINK']
                                        ]) ?>
                                            <span class="widget-all-desktop intec-cl-text-hover">
                                                <?php if (empty($arBlocks['FOOTER']['TEXT'])) { ?>
                                                    <?= Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_15_TEMPLATE_FOOTER_TEXT_DEFAULT') ?>
                                                <?php } else { ?>
                                                    <?= $arBlocks['FOOTER']['TEXT'] ?>
                                                <?php } ?>
                                            </span>
                                            <span class="widget-all-mobile intec-ui-picture intec-cl-svg-path-stroke-hover">
                                                <?= FileHelper::getFileData(__DIR__.'/svg/all.mobile.svg') ?>
                                            </span>
                                        <?= Html::endTag('a') ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                    <?php if ($arBlocks['DESCRIPTION']['SHOW']) { ?>
                        <?= Html::tag('div', $arBlocks['DESCRIPTION']['TEXT'], [
                            'class' => [
                                'widget-description',
                                'align-'.(
                                    $arBlocks['FOOTER']['SHOW'] || $arVisual['SEND']['USE'] ? 'left' : $arBlocks['DESCRIPTION']['POSITION']
                                )
                            ]
                        ]) ?>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="widget-content">
                <?= Html::beginTag('div', [
                    'class' => [
                        'widget-items',
                        'owl-carousel'
                    ],
                    'data-role' => 'container'
                ]) ?>
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                        if (!$arItem['DATA']['PREVIEW']['SHOW'])
                            continue;

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                        $sPicture = $arItem['PREVIEW_PICTURE'];

                        if (empty($sPicture))
                            $sPicture = $arItem['DETAIL_PICTURE'];

                        if (!empty($sPicture)) {
                            $sPicture = CFile::ResizeImageGet($sPicture, [
                                'width' => 128,
                                'height' => 128
                            ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                            if (!empty($sPicture))
                                $sPicture = $sPicture['src'];
                        }

                        if (empty($sPicture))
                            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                        $sTag = !empty($arItem['DETAIL_PAGE_URL']) && $arVisual['LINK']['USE'] ? 'a' : 'div';

                    ?>
                        <div class="widget-item" id="<?= $sAreaId ?>">
                            <div class="intec-grid intec-grid-nowrap intec-grid-o-vertical intec-grid-a-h-between widget-item-wrapper">
                                <div class="widget-item-image-wrap">
                                    <?= Html::tag($sTag, null, [
                                        'class' => [
                                            'widget-item-image',
                                            'intec-image-effect'
                                        ],
                                        'href' => $sTag === 'a' ? $arItem['DETAIL_PAGE_URL'] : null,
                                        'target' => $sTag === 'a' && $arVisual['LINK']['BLANK'] ? '_blank' : null,
                                        'data' => [
                                            'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                            'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                        ],
                                        'style' => [
                                            'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                        ]
                                    ]) ?>
                                </div>
                                <div class="widget-item-content" data-role="slide.content">
                                    <div class="widget-item-description">
                                        <?= $arItem['DATA']['PREVIEW']['VALUE'] ?>
                                    </div>
                                    <?= Html::tag($sTag, $arItem['NAME'], [
                                        'class' => 'widget-item-name',
                                        'href' => $sTag === 'a' ? $arItem['DETAIL_PAGE_URL'] : null,
                                        'target' => $sTag === 'a' && $arVisual['LINK']['BLANK'] ? '_blank' : null
                                    ]) ?>
                                    <?php if ($arItem['DATA']['RATING']['SHOW']) {

                                        $isMatch = false;

                                    ?>
                                        <div class="widget-item-rating">
                                            <div class="intec-grid intec-grid-i-h-3 intec-grid-a-h-center">
                                                <?php foreach ($arResult['RATING'] as $key => $value) { ?>
                                                    <?= Html::tag('div', $arSvg['RATING'], [
                                                        'class' => [
                                                            'widget-item-rating-item',
                                                            'intec-grid-item-auto',
                                                            'intec-ui-picture'
                                                        ],
                                                        'title' => ArrayHelper::getValue(
                                                            $arResult['RATING'],
                                                            $arItem['DATA']['RATING']['VALUE']
                                                        ),
                                                        'data-active' => !$isMatch ? 'true' : 'false'
                                                    ]) ?>
                                                    <?php if ($key == $arItem['DATA']['RATING']['VALUE'])
                                                        $isMatch = true;
                                                    ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?= Html::endTag('div') ?>
                <div class="widget-slide-content" data-role="slider.content"></div>
                <div class="widget-navigation" data-role="slider.navigation">
                    <?= Html::beginTag('span', [
                        'class' => [
                            'widget-button-left',
                            'intec-cl-background-hover',
                            'intec-cl-border-hover'
                        ],
                        'data-role' => 'slider.navigation.left'
                    ]) ?>
                        <i class="far fa-angle-left"></i>
                    <?= Html::endTag('span') ?>
                    <?= Html::beginTag('span', [
                        'class' => [
                            'widget-button-left',
                            'intec-cl-background-hover',
                            'intec-cl-border-hover'
                        ],
                        'data-role' => 'slider.navigation.right'
                    ]) ?>
                        <i class="far fa-angle-right"></i>
                    <?= Html::endTag('span') ?>
                </div>
            </div>
        </div>
    </div>
    <?php include(__DIR__.'/parts/script.php') ?>
</div>
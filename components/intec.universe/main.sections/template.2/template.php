<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\FileHelper;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (empty($arResult['SECTIONS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arBlocks = $arResult['BLOCKS'];
$arVisual = $arResult['VISUAL'];

?>
<div class="widget c-sections c-sections-template-2" id="<?= $sTemplateId ?>">
    <div class="widget-wrapper intec-content intec-content-visible">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW'] || $arVisual['BUTTON_SHOW_ALL']['SHOW']) { ?>
                <div class="widget-header">
                    <div class="intec-grid intec-grid-wrap intec-grid-a-v-center intec-grid-i-8">
                        <?php if ($arBlocks['HEADER']['SHOW']) { ?>
                            <div class="widget-title-container intec-grid-item">
                                <?= Html::tag('div', Html::encode($arBlocks['HEADER']['TEXT']), [
                                    'class' => [
                                        'widget-title',
                                        'align-'.$arBlocks['HEADER']['POSITION'],
                                        $arVisual['BUTTON_SHOW_ALL']['SHOW'] ? 'widget-title-margin' : null
                                    ]
                                ]) ?>
                            </div>
                        <?php } ?>
                        <?php if ($arVisual['BUTTON_SHOW_ALL']['SHOW']) { ?>
                            <?= Html::beginTag('div', [
                                'class' => Html::cssClassFromArray([
                                    'widget-all-container' => true,
                                    'mobile' => $arBlocks['HEADER']['SHOW'],
                                    'intec-grid-item' => [
                                        'auto' => $arBlocks['HEADER']['SHOW'],
                                        '1' => !$arBlocks['HEADER']['SHOW']
                                    ]
                                ], true)
                            ]) ?>
                            <?= Html::beginTag('a', [
                                'class' => [
                                    'widget-all-button',
                                    'intec-cl-text-light-hover',
                                ],
                                'href' => $arVisual['BUTTON_SHOW_ALL']['LINK']
                            ])?>
                            <span><?= $arVisual['BUTTON_SHOW_ALL']['TEXT'] ?></span>
                            <i class="fal fa-angle-right"></i>
                            <?= Html::endTag('a')?>
                            <?= Html::endTag('div') ?>
                        <?php } ?>
                        <?php if ($arBlocks['DESCRIPTION']['SHOW']) { ?>
                            <div class="widget-description-container intec-grid-item-1">
                                <div class="widget-description align-<?= $arBlocks['DESCRIPTION']['POSITION'] ?>">
                                    <?= Html::encode($arBlocks['DESCRIPTION']['TEXT']) ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <?= Html::beginTag('div', [
                'class' => [
                    'widget-content',
                    'intec-grid' => [
                        '',
                        'wrap',
                        'a-v-stretch',
                        'a-h-center'
                    ]
                ]
            ]) ?>
                <?php foreach ($arResult['SECTIONS'] as $arItem) {

                    $sId = $sTemplateId.'_'.$arItem['ID'];
                    $sAreaId = $this->GetEditAreaId($sId);
                    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                    $arPicture = [
                        'TYPE' => 'picture',
                        'SOURCE' => null,
                        'ALT' => null,
                        'TITLE' => null
                    ];

                    if (!empty($arItem['PICTURE'])) {
                        if ($arItem['PICTURE']['CONTENT_TYPE'] === 'image/svg+xml') {
                            $arPicture['TYPE'] = 'svg';
                            $arPicture['SOURCE'] = $arItem['PICTURE']['SRC'];
                        } else {
                            $arPicture['SOURCE'] = CFile::ResizeImageGet($arItem['PICTURE'], [
                                'width' => 450,
                                'height' => 450
                            ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                            if (!empty($arPicture['SOURCE'])) {
                                $arPicture['SOURCE'] = $arPicture['SOURCE']['src'];
                            } else {
                                $arPicture['SOURCE'] = null;
                            }
                        }
                    }

                    if (empty($arPicture['SOURCE'])) {
                        $arPicture['TYPE'] = 'picture';
                        $arPicture['SOURCE'] = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
                    } else {
                        $arPicture['ALT'] = $arItem['PICTURE']['ALT'];
                        $arPicture['TITLE'] = $arItem['PICTURE']['TITLE'];
                    }

                ?>
                    <?= Html::beginTag('div', [
                        'class' => Html::cssClassFromArray([
                            'widget-element-wrap' => true,
                            'intec-grid-item' => [
                                $arVisual['COLUMNS'] => true,
                                '768-2' => $arVisual['COLUMNS'] >= 3,
                                '550-1' => true
                            ]
                        ], true)
                    ]) ?>
                        <?= Html::beginTag('div', [
                            'id' => $sAreaId,
                            'class' => [
                                'widget-element',
                                'intec-grid' => [
                                    '',
                                    'a-v-stretch',
                                    'a-h-center'
                                ]
                            ],
                            'data' => [
                                'picture-size' => $arVisual['PICTURE']['SIZE']
                            ]
                        ]) ?>
                            <div class="widget-element-picture-wrap intec-grid-item-auto">
                                <?= Html::beginTag('a', [
                                    'class' => Html::cssClassFromArray([
                                        'widget-element-picture' => true,
                                        'intec-ui-picture' => true,
                                        'intec-cl-svg' => $arVisual['SVG']['COLOR'] == 'theme' ? true : false,
                                    ], true),
                                    'href' => $arItem['SECTION_PAGE_URL']
                                ]) ?>
                                    <?php if ($arPicture['TYPE'] === 'svg') { ?>
                                        <?= FileHelper::getFileData('@root/'.$arPicture['SOURCE']) ?>
                                    <?php } else { ?>
                                        <?= Html::img($arPicture['SOURCE'], [
                                            'alt' => !empty($arItem['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_ALT']) ? $arItem['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_ALT'] : $arItem['NAME'],
                                            'title' => !empty($arItem['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_TITLE']) ? $arItem['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_TITLE'] : $arItem['NAME'],
                                            'loading' => 'lazy',
                                            'data' => [
                                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                            ]
                                        ]) ?>
                                    <?php } ?>
                                <?= Html::endTag('a') ?>
                            </div>
                            <div class="widget-element-text intec-grid-item">
                                <a href="<?= $arItem['SECTION_PAGE_URL'] ?>" class="widget-element-name intec-cl-text-hover">
                                    <?= $arItem['NAME'] ?>
                                </a>
                                <?php if ($arVisual['CHILDREN']['SHOW'] && !empty($arItem['SECTIONS'])) { ?>
                                    <div class="widget-element-section">
                                        <?php $iCount = 0 ?>
                                        <?php foreach ($arItem['SECTIONS'] as $arSection) {

                                            ++$iCount;

                                            if ($arVisual['CHILDREN']['COUNT'] !== null && $iCount > $arVisual['CHILDREN']['COUNT'])
                                                break;

                                        ?>
                                            <div class="widget-element-section-element">
                                                <?= Html::tag('a', $arSection['NAME'], [
                                                    'class' => [
                                                        'widget-element-section-name',
                                                        'intec-cl-text-hover'
                                                    ],
                                                    'href' => $arSection['SECTION_PAGE_URL']
                                                ]) ?>
                                                <?php if ($arVisual['QUANTITY']['SHOW']) { ?>
                                                    <span class="widget-element-section-count">
                                                        <?= $arSection['ELEMENT_CNT'] ?>
                                                    </span>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            <?= Html::endTag('div') ?>
        </div>
    </div>
</div>
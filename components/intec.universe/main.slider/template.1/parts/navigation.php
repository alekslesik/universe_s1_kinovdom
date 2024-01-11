<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

/**
 * @var array $arVisual
 * @var bool $bSliderUse
 */

if (!$bSliderUse)
    return;

?>
<?php if ($arVisual['SLIDER']['NAV']['SHOW']) { ?>
    <?= Html::tag('div', '', [
        'class' => 'widget-slider-nav',
        'data-role' => 'container.nav'
    ]) ?>
<?php } ?>
<?php if ($arVisual['SLIDER']['DOTS']['SHOW']) { ?>
    <?= Html::tag('div', '', [
        'class' => 'widget-slider-dots',
        'data-role' => 'container.dots'
    ]) ?>
<?php } ?>
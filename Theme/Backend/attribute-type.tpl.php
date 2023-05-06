<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Tasks
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use phpOMS\Localization\ISO639Enum;

$attribute = $this->getData('attribute');
$l11ns = $this->getData('l11ns');

echo $this->getData('nav')->render(); ?>

<div class="row">
    <div class="col-md-6 col-xs-12">
        <section id="task" class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Attribute'); ?></div>

            <div class="portlet-body">
            <div class="form-group">
                    <label for="iId"><?= $this->getHtml('ID'); ?></label>
                    <input type="text" value="<?= $this->printHtml((string) $attribute->id); ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="iName"><?= $this->getHtml('Name'); ?></label>
                    <input type="text" value="<?= $this->printHtml($attribute->name); ?>" disabled>
                </div>
            </div>
        </section>
    </div>

    <div class="col-xs-12 col-md-6">
        <div class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Language', '0', '0'); ?><i class="fa fa-download floatRight download btn"></i></div>
            <table class="default">
                <thead>
                    <tr>
                        <td>
                        <td>
                        <td><?= $this->getHtml('Language', '0', '0'); ?>
                        <td class="wf-100"><?= $this->getHtml('Title'); ?>
                <tbody>
                    <?php $c = 0; foreach ($l11ns as $key => $value) : ++$c; ?>
                    <tr>
                        <td><a href="#"><i class="fa fa-times"></i></a>
                        <td><a href="#"><i class="fa fa-cogs"></i></a>
                        <td><?= ISO639Enum::getByName('_' . \strtoupper($value->getLanguage())); ?>
                        <td><?= $value->content; ?>
                    <?php endforeach; ?>
                    <?php if ($c === 0) : ?>
                    <tr><td colspan="3" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                    <?php endif; ?>
            </table>
        </div>
    </div>
</div>

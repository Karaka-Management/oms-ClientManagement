<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Tasks
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\Attribute\Models\AttributeValueType;
use phpOMS\Localization\ISO639Enum;

$types = AttributeValueType::getConstants();

$attribute = $this->data['attribute'];
$l11ns     = $this->data['l11ns'];

echo $this->data['nav']->render(); ?>

<div class="row">
    <div class="col-md-6 col-xs-12">
        <section id="task" class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Attribute', 'Attribute', 'Backend'); ?></div>

            <div class="portlet-body">
            <div class="form-group">
                    <label for="iId"><?= $this->getHtml('ID', '0', '0'); ?></label>
                    <input type="text" value="<?= $this->printHtml((string) $attribute->id); ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="iName"><?= $this->getHtml('Name', 'Attribute', 'Backend'); ?></label>
                    <input id="iNAme" type="text" value="<?= $this->printHtml($attribute->name); ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="iType"><?= $this->getHtml('Datatype', 'Attribute', 'Backend'); ?></label>
                    <select id="iType" name="type" disabled>
                        <?php foreach ($types as $key => $type) : ?>
                            <option value="<?= $type; ?>"<?= $type === $attribute->datatype ? ' selected' : ''; ?>><?= $this->printHtml($key); ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="iPattern"><?= $this->getHtml('Pattern', 'Attribute', 'Backend'); ?></label>
                    <input id="iPattern" type="text" value="<?= $this->printHtml($attribute->validationPattern); ?>">
                </div>

                <div class="form-group">
                    <label class="checkbox" for="iRequired">
                        <input id="iRequired" type="checkbox" name="required" value="1"<?= $attribute->isRequired ? ' checked' : ''; ?>>
                        <span class="checkmark"></span>
                        <?= $this->getHtml('IsRequired', 'Attribute', 'Backend'); ?>
                    </label>
                </div>

                <div class="form-group">
                    <label class="checkbox" for="iCustom">
                        <input id="iCustom" type="checkbox" name="custom" value="1" <?= $attribute->custom ? ' checked' : ''; ?>>
                        <span class="checkmark"></span>
                        <?= $this->getHtml('CustomValue', 'Attribute', 'Backend'); ?>
                    </label>
                </div>
            </div>
        </section>
    </div>

    <div class="col-xs-12 col-md-6">
        <div class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Language', '0', '0'); ?><i class="g-icon download btn end-xs">download</i></div>
            <table class="default sticky">
                <thead>
                    <tr>
                        <td>
                        <td>
                        <td><?= $this->getHtml('Language', '0', '0'); ?>
                        <td class="wf-100"><?= $this->getHtml('Title', 'Attribute', 'Backend'); ?>
                <tbody>
                    <?php $c = 0; foreach ($l11ns as $key => $value) : ++$c; ?>
                    <tr>
                        <td><a href="#"><i class="g-icon">close</i></a>
                        <td><a href="#"><i class="g-icon">settings</i></a>
                        <td><?= ISO639Enum::getByName('_' . \strtoupper($value->language)); ?>
                        <td><?= $value->content; ?>
                    <?php endforeach; ?>
                    <?php if ($c === 0) : ?>
                    <tr><td colspan="3" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                    <?php endif; ?>
            </table>
        </div>
    </div>
</div>

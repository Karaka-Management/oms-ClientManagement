<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\ItemManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use phpOMS\Uri\UriFactory;

/** @var \phpOMS\Views\View $this */
$attributes = $this->data['attributes'];

echo $this->data['nav']->render(); ?>

<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $this->getHtml('AttributeTypes'); ?><i class="lni lni-download download btn end-xs"></i></div>
            <div class="slider">
            <table id="iAttributeTypeList" class="default sticky">
                <thead>
                <tr>
                    <td><?= $this->getHtml('ID', '0', '0'); ?>
                        <label for="iAttributeTypeList-sort-1">
                            <input type="radio" name="iAttributeTypeList-sort" id="iAttributeTypeList-sort-1">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="iAttributeTypeList-sort-2">
                            <input type="radio" name="iAttributeTypeList-sort" id="iAttributeTypeList-sort-2">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td class="wf-100"><?= $this->getHtml('Name'); ?>
                        <label for="iAttributeTypeList-sort-2">
                            <input type="radio" name="iAttributeTypeList-sort" id="iAttributeTypeList-sort-2">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="iAttributeTypeList-sort-3">
                            <input type="radio" name="iAttributeTypeList-sort" id="iAttributeTypeList-sort-3">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <tbody>
                <?php
                $count = 0;
                foreach ($attributes as $key => $value) : ++$count;
                    $url = UriFactory::build('{/base}/sales/client/attribute/type?{?}&id=' . $value->id);
                ?>
                <tr data-href="<?= $url; ?>">
                    <td><a href="<?= $url; ?>"><?= $value->id; ?></a>
                    <td><a href="<?= $url; ?>"><?= $this->printHtml($value->getL11n()); ?></a>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="2" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
            </div>
        </section>
    </div>
</div>

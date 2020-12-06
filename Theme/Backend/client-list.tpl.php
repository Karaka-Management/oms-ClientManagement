<?php

/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\ClientManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

use phpOMS\Uri\UriFactory;

/** @var \phpOMS\Views\View $this */
$clients = $this->getData('client');

echo $this->getData('nav')->render(); ?>
<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Clients'); ?><i class="fa fa-download floatRight download btn"></i></div>
            <table id="iSalesClientList" class="default">
                <thead>
                <tr>
                    <td><?= $this->getHtml('ID', '0', '0'); ?>
                        <input id="clientList-r1-asc" name="clientList-sort" type="radio"><label for="clientList-r1-asc"><i class="sort-asc fa fa-chevron-up"></i></label>
                        <input id="clientList-r1-desc" name="clientList-sort" type="radio"><label for="clientList-r1-desc"><i class="sort-desc fa fa-chevron-down"></i></label>
                    <td class="wf-100"><?= $this->getHtml('Name'); ?>
                        <input id="clientList-r2-asc" name="clientList-sort" type="radio"><label for="clientList-r2-asc"><i class="sort-asc fa fa-chevron-up"></i></label>
                        <input id="clientList-r2-desc" name="clientList-sort" type="radio"><label for="clientList-r2-desc"><i class="sort-desc fa fa-chevron-down"></i></label>
                    <td><?= $this->getHtml('City'); ?>
                        <input id="clientList-r5-asc" name="clientList-sort" type="radio"><label for="clientList-r5-asc"><i class="sort-asc fa fa-chevron-up"></i></label>
                        <input id="clientList-r5-desc" name="clientList-sort" type="radio"><label for="clientList-r5-desc"><i class="sort-desc fa fa-chevron-down"></i></label>
                    <td><?= $this->getHtml('Zip'); ?>
                        <input id="clientList-r6-asc" name="clientList-sort" type="radio"><label for="clientList-r6-asc"><i class="sort-asc fa fa-chevron-up"></i></label>
                        <input id="clientList-r6-desc" name="clientList-sort" type="radio"><label for="clientList-r6-desc"><i class="sort-desc fa fa-chevron-down"></i></label>
                    <td><?= $this->getHtml('Address'); ?>
                        <input id="clientList-r7-asc" name="clientList-sort" type="radio"><label for="clientList-r7-asc"><i class="sort-asc fa fa-chevron-up"></i></label>
                        <input id="clientList-r7-desc" name="clientList-sort" type="radio"><label for="clientList-r7-desc"><i class="sort-desc fa fa-chevron-down"></i></label>
                    <td><?= $this->getHtml('Country'); ?>
                        <input id="clientList-r8-asc" name="clientList-sort" type="radio"><label for="clientList-r8-asc"><i class="sort-asc fa fa-chevron-up"></i></label>
                        <input id="clientList-r8-desc" name="clientList-sort" type="radio"><label for="clientList-r8-desc"><i class="sort-desc fa fa-chevron-down"></i></label>
                <tbody>
                <?php $count = 0; foreach ($clients as $key => $value) : ++$count;
                 $url        = UriFactory::build('{/prefix}sales/client/profile?{?}&id=' . $value->getId()); ?>
                <tr data-href="<?= $url; ?>">
                    <td data-label="<?= $this->getHtml('ID', '0', '0'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->number); ?></a>
                    <td data-label="<?= $this->getHtml('Name'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->profile->account->name1); ?> <?= $this->printHtml($value->profile->account->name2); ?></a>
                    <td data-label="<?= $this->getHtml('City'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->mainAddress->city); ?></a>
                    <td data-label="<?= $this->getHtml('Zip'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->mainAddress->postal); ?></a>
                    <td data-label="<?= $this->getHtml('Address'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->mainAddress->address); ?></a>
                    <td data-label="<?= $this->getHtml('Country'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->mainAddress->getCountry()); ?></a>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="8" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
        </section>
    </div>
</div>

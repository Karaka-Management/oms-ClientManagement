<?php

/**
 * Orange Management
 *
 * PHP Version 7.4
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
            <table class="default">
                <thead>
                <tr>
                    <td><?= $this->getHtml('ID', '0', '0'); ?>
                    <td><?= $this->getHtml('Name1'); ?>
                    <td><?= $this->getHtml('Name2'); ?>
                    <td class="wf-100"><?= $this->getHtml('Name3'); ?>
                    <td><?= $this->getHtml('City'); ?>
                    <td><?= $this->getHtml('Zip'); ?>
                    <td><?= $this->getHtml('Address'); ?>
                    <td><?= $this->getHtml('Country'); ?>
                <tbody>
                <?php $count = 0; foreach ($clients as $key => $value) : ++$count;
                 $url        = UriFactory::build('{/prefix}sales/client/profile?{?}&id=' . $value->getId()); ?>
                <tr data-href="<?= $url; ?>">
                    <td data-label="<?= $this->getHtml('ID', '0', '0'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->getNumber()); ?></a>
                    <td data-label="<?= $this->getHtml('Name1'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->getProfile()->getAccount()->getName1()); ?></a>
                    <td data-label="<?= $this->getHtml('Name2'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->getProfile()->getAccount()->getName2()); ?></a>
                    <td data-label="<?= $this->getHtml('Name3'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->getProfile()->getAccount()->getName3()); ?></a>
                    <td data-label="<?= $this->getHtml('City'); ?>">
                    <td data-label="<?= $this->getHtml('Zip'); ?>">
                    <td data-label="<?= $this->getHtml('Address'); ?>">
                    <td data-label="<?= $this->getHtml('Country'); ?>">
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="8" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
        </section>
    </div>
</div>

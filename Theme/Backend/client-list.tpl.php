<?php

/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\ClientManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use phpOMS\Uri\UriFactory;

/** @var \phpOMS\Views\View $this */
$clients = $this->data['client'];

echo $this->data['nav']->render(); ?>
<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head">
                <?= $this->getHtml('Clients'); ?>
                <i class="g-icon download btn end-xs">download</i>
                <a class="button end-xs" href="<?= UriFactory::build('{%}?geo=\{°\}'); ?>"><i class="g-icon">location_on</i></a>
            </div>
            <div class="slider">
            <table id="iSalesClientList" class="default sticky">
                <thead>
                <tr>
                    <td><?= $this->getHtml('ID', '0', '0'); ?>
                        <label for="iSalesClientList-sort-1">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-1">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="iSalesClientList-sort-2">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-2">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                    <td class="wf-100"><?= $this->getHtml('Name'); ?>
                        <label for="iSalesClientList-sort-3">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-3">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="iSalesClientList-sort-4">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-4">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                    <td><?= $this->getHtml('City'); ?>
                        <label for="iSalesClientList-sort-5">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-5">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="iSalesClientList-sort-6">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-6">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                    <td><?= $this->getHtml('Postal'); ?>
                        <label for="iSalesClientList-sort-7">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-7">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="iSalesClientList-sort-8">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-8">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                    <td><?= $this->getHtml('Address'); ?>
                        <label for="iSalesClientList-sort-9">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-9">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="iSalesClientList-sort-10">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-10">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                    <td><?= $this->getHtml('Country'); ?>
                        <label for="iSalesClientList-sort-11">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-11">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="iSalesClientList-sort-12">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-12">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                <tbody>
                <?php $count = 0;
                foreach ($clients as $key => $value) : ++$count;
                    $url = UriFactory::build('{/base}/sales/client/view?{?}&id=' . $value->id);
                ?>
                <tr data-href="<?= $url; ?>">
                    <td data-label="<?= $this->getHtml('ID', '0', '0'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->number); ?></a>
                    <td data-label="<?= $this->getHtml('Name'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->account->name1); ?> <?= $this->printHtml($value->account->name2); ?></a>
                    <td data-label="<?= $this->getHtml('City'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->mainAddress->city); ?></a>
                    <td data-label="<?= $this->getHtml('Postal'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->mainAddress->postal); ?></a>
                    <td data-label="<?= $this->getHtml('Address'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->mainAddress->address); ?></a>
                    <td data-label="<?= $this->getHtml('Country'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->mainAddress->country); ?></a>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="8" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
            </div>
        </section>
    </div>
</div>

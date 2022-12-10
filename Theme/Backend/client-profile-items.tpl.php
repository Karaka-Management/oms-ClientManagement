<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\ItemManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

use phpOMS\Uri\UriFactory;

/** @var \phpOMS\Views\View $this */
$items = $this->getData('items') ?? [];

?>

<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Items'); ?><i class="fa fa-download floatRight download btn"></i></div>
            <table id="iSalesItemList" class="default">
                <thead>
                <tr>
                    <td><label class="checkbox" for="iSalesItemSelect-">
                            <input type="checkbox" id="iSalesItemSelect-" name="itemselect">
                            <span class="checkmark"></span>
                        </label>
                    <td><?= $this->getHtml('ID', '0', '0'); ?>
                        <label for="iSalesItemList-sort-1">
                            <input type="radio" name="iSalesItemList-sort" id="iSalesItemList-sort-1">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="iSalesItemList-sort-2">
                            <input type="radio" name="iSalesItemList-sort" id="iSalesItemList-sort-2">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td class="wf-100"><?= $this->getHtml('Name'); ?>
                        <label for="iSalesItemList-sort-3">
                            <input type="radio" name="iSalesItemList-sort" id="iSalesItemList-sort-3">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="iSalesItemList-sort-4">
                            <input type="radio" name="iSalesItemList-sort" id="iSalesItemList-sort-4">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('Quantity'); ?>
                        <label for="iSalesItemList-sort-5">
                            <input type="radio" name="iSalesItemList-sort" id="iSalesItemList-sort-5">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="iSalesItemList-sort-6">
                            <input type="radio" name="iSalesItemList-sort" id="iSalesItemList-sort-6">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('UnitPrice'); ?>
                        <label for="iSalesItemList-sort-7">
                            <input type="radio" name="iSalesItemList-sort" id="iSalesItemList-sort-7">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="iSalesItemList-sort-8">
                            <input type="radio" name="iSalesItemList-sort" id="iSalesItemList-sort-8">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('Discount'); ?>
                        <label for="iSalesItemList-sort-11">
                            <input type="radio" name="iSalesItemList-sort" id="iSalesItemList-sort-11">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="iSalesItemList-sort-12">
                            <input type="radio" name="iSalesItemList-sort" id="iSalesItemList-sort-12">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('Discount%'); ?>
                        <label for="iSalesItemList-sort-13">
                            <input type="radio" name="iSalesItemList-sort" id="iSalesItemList-sort-13">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="iSalesItemList-sort-14">
                            <input type="radio" name="iSalesItemList-sort" id="iSalesItemList-sort-14">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('DiscountBonus'); ?>
                        <label for="iSalesItemList-sort-15">
                            <input type="radio" name="iSalesItemList-sort" id="iSalesItemList-sort-15">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="iSalesItemList-sort-16">
                            <input type="radio" name="iSalesItemList-sort" id="iSalesItemList-sort-16">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('TotalPrice'); ?>
                        <label for="iSalesItemList-sort-9">
                            <input type="radio" name="iSalesItemList-sort" id="iSalesItemList-sort-9">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="iSalesItemList-sort-10">
                            <input type="radio" name="iSalesItemList-sort" id="iSalesItemList-sort-10">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                <tbody>
                <?php
                    $count = 0;
                    foreach ($items as $key => $value) :
                        if ($value->itemNumber === '') {
                            continue;
                        }

                        ++$count;
                        $url = UriFactory::build('{/lang}/{/app}/sales/item/profile?{?}&id=' . $value->getId());
                ?>
                <tr data-href="<?= $url; ?>">
                    <td><label class="checkbox" for="iSalesItemSelect-<?= $key; ?>">
                                    <input type="checkbox" id="iSalesItemSelect-<?= $key; ?>" name="itemselect">
                                    <span class="checkmark"></span>
                                </label>
                    <td><a href="<?= $url; ?>"><?= $this->printHtml($value->itemNumber); ?></a>
                    <td><a href="<?= $url; ?>"><?= $this->printHtml($value->itemName); ?></a>
                    <td><a href="<?= $url; ?>"><?= $this->printHtml((string) $value->quantity); ?></a>
                    <td><a href="<?= $url; ?>"><?= $this->printHtml($value->singleSalesPriceNet->getCurrency()); ?></a>
                    <td>
                    <td>
                    <td>
                    <td><a href="<?= $url; ?>"><?= $this->printHtml($value->totalSalesPriceNet->getCurrency()); ?></a>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="9" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
        </section>
    </div>
</div>

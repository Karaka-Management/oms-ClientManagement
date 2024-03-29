<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\ClientManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

/**
 * @var \phpOMS\Views\View $this
 */
echo $this->data['nav']->render(); ?>

<div class="tabview tab-2">
    <div class="box">
        <ul class="tab-links">
            <li><label for="c-tab-1"><?= $this->getHtml('Profile'); ?></label>
            <li><label for="c-tab-2"><?= $this->getHtml('Contact'); ?></label>
            <li><label for="c-tab-3"><?= $this->getHtml('Addresses'); ?></label>
            <li><label for="c-tab-4"><?= $this->getHtml('PaymentTerm'); ?></label>
            <li><label for="c-tab-5"><?= $this->getHtml('Payment'); ?></label>
            <li><label for="c-tab-6"><?= $this->getHtml('Prices'); ?></label>
            <li><label for="c-tab-7"><?= $this->getHtml('AreaManager'); ?></label>
            <li><label for="c-tab-8"><?= $this->getHtml('Files'); ?></label>
            <li><label for="c-tab-9"><?= $this->getHtml('Logs'); ?></label>
        </ul>
    </div>
    <div class="tab-content">
        <input type="radio" id="c-tab-1" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-1' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <section class="box wf-100">
                        <header><h1><?= $this->getHtml('Client'); ?></h1></header>
                        <div class="inner">
                            <form>
                                <table class="layout wf-100">
                                    <tr><td><label for="iId"><?= $this->getHtml('ID', '0', '0'); ?></label>
                                    <tr><td><span class="input"><button type="button" formaction=""><i class="g-icon">book</i></button><input type="number" id="iId" min="1" name="id" required></span>
                                    <tr><td><label for="iName1"><?= $this->getHtml('Name1'); ?></label>
                                    <tr><td><input type="text" id="iName1" name="name1" placeholder="" required>
                                    <tr><td><label for="iName2"><?= $this->getHtml('Name2'); ?></label>
                                    <tr><td><input type="text" id="iName2" name="name2" placeholder="">
                                    <tr><td><label for="iName3"><?= $this->getHtml('Name3'); ?></label>
                                    <tr><td><input type="text" id="iName3" name="name3" placeholder="">
                                    <tr><td colspan="2"><input type="submit" value="<?= $this->getHtml('Create', '0', '0'); ?>" name="create-client">
                                </table>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <input type="radio" id="c-tab-2" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-2' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <section class="box wf-100">
                        <header><h1><?= $this->getHtml('Contact'); ?></h1></header>
                        <div class="inner">
                            <form>
                                <table class="layout wf-100">
                                    <tr><td><label for="iCType"><?= $this->getHtml('Type'); ?></label>
                                    <tr><td><select id="iCType" name="actype">
                                                <option><?= $this->getHtml('Email'); ?>
                                                <option><?= $this->getHtml('Fax'); ?>
                                                <option><?= $this->getHtml('Phone'); ?>
                                            </select>
                                    <tr><td><label for="iCStype"><?= $this->getHtml('Subtype'); ?></label>
                                    <tr><td><select id="iCStype" name="acstype">
                                                <option><?= $this->getHtml('Office'); ?>
                                                <option><?= $this->getHtml('Sales'); ?>
                                                <option><?= $this->getHtml('Purchase'); ?>
                                                <option><?= $this->getHtml('Accounting'); ?>
                                                <option><?= $this->getHtml('Support'); ?>
                                            </select>
                                    <tr><td><label for="iCInfo"><?= $this->getHtml('Info'); ?></label>
                                    <tr><td><input type="text" id="iCInfo" name="cinfo">
                                    <tr><td><label for="iCData"><?= $this->getHtml('Contact'); ?></label>
                                    <tr><td><input type="text" id="iCData" name="cdata">
                                    <tr><td colspan="2"><input type="submit" value="<?= $this->getHtml('Add', '0', '0'); ?>">
                                </table>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <input type="radio" id="c-tab-3" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-3' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <section class="box wf-100">
                        <header><h1><?= $this->getHtml('Address'); ?></h1></header>
                        <div class="inner">
                            <form>
                                <table class="layout wf-100">
                                    <tr><td><label for="iAType"><?= $this->getHtml('Type'); ?></label>
                                    <tr><td><select id="iAType" name="atype">
                                                <option><?= $this->getHtml('Default'); ?>
                                                <option><?= $this->getHtml('Delivery'); ?>
                                                <option><?= $this->getHtml('Invoice'); ?>
                                            </select>
                                    <tr><td><label for="iAddress"><?= $this->getHtml('Address'); ?></label>
                                    <tr><td><input type="text" id="iAddress" name="address">
                                    <tr><td><label for="iZip"><?= $this->getHtml('Zip'); ?></label>
                                    <tr><td><input type="text" id="iZip" name="zip">
                                    <tr><td><label for="iCountry"><?= $this->getHtml('Country'); ?></label>
                                    <tr><td><input type="text" id="iCountry" name="country">
                                    <tr><td><label for="iAInfo"><?= $this->getHtml('Info'); ?></label>
                                    <tr><td><input type="text" id="iAInfo" name="ainfo">
                                    <tr><td><span class="check"><input type="checkbox" id="iDefault" name="default" checked><label for="iDefault"><?= $this->getHtml('IsDefault'); ?></label></span>
                                    <tr><td colspan="2"><input type="submit" value="<?= $this->getHtml('Add', '0', '0'); ?>">
                                </table>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <input type="radio" id="c-tab-4" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-4' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <section class="box wf-100">
                        <header><h1><?= $this->getHtml('PaymentTerm'); ?></h1></header>
                        <div class="inner">
                            <form>
                                <table class="layout wf-100">
                                    <tr><td><label for="iSource"><?= $this->getHtml('ID'); ?></label>
                                    <tr><td><span class="input"><button type="button" formaction=""><i class="g-icon">book</i></button><input id="iSource" name="source" type="text" placeholder=""></span>
                                    <tr><td><label for="iSegment"><?= $this->getHtml('Segment'); ?></label>
                                    <tr><td><input id="iSegment" name="segment" type="text" placeholder="">
                                    <tr><td><label for="iProductgroup"><?= $this->getHtml('Productgroup'); ?></label>
                                    <tr><td><input id="iProductgroup" name="productgroup" type="text" placeholder="">
                                    <tr><td><label for="iGroup"><?= $this->getHtml('Group'); ?></label>
                                    <tr><td><input id="iGroup" name="group" type="text" placeholder="">
                                    <tr><td><label for="iArticlegroup"><?= $this->getHtml('Articlegroup'); ?></label>
                                    <tr><td><input id="iArticlegroup" name="articlegroup" type="text" placeholder="">
                                    <tr><td><label for="iTerm"><?= $this->getHtml('Type'); ?></label>
                                    <tr><td><select id="iTerm" name="term" required>
                                                <option>
                                            </select>
                                    <tr><td><span class="check"><input type="checkbox" id="iFreightage" name="freightage"><label for="iFreightage"><?= $this->getHtml('Freightage'); ?></label></span>
                                    <tr><td colspan="2"><input type="submit" value="<?= $this->getHtml('Add', '0', '0'); ?>">
                                </table>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <input type="radio" id="c-tab-5" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-5' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <section class="box wf-100">
                        <header><h1><?= $this->getHtml('Payment'); ?></h1></header>
                        <div class="inner">
                            <form>
                                <table class="layout wf-100">
                                    <tr><td><label for="iACType"><?= $this->getHtml('Type'); ?></label>
                                    <tr><td><select id="iACType" name="actype">
                                                <option><?= $this->getHtml('Wire'); ?>
                                                <option><?= $this->getHtml('Creditcard'); ?>
                                            </select>
                                    <tr><td colspan="2"><input type="submit" value="<?= $this->getHtml('Add', '0', '0'); ?>">
                                </table>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <input type="radio" id="c-tab-6" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-6' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <section class="box wf-100">
                        <header><h1><?= $this->getHtml('Price'); ?></h1></header>
                        <div class="inner">
                            <form action="<?= \phpOMS\Uri\UriFactory::build('{/api}...'); ?>" method="post">
                                <table class="layout wf-100">
                                    <tbody>
                                    <tr><td colspan="2"><label for="iPType"><?= $this->getHtml('Type'); ?></label>
                                    <tr><td><select id="iPType" name="ptye">
                                                <option>
                                            </select><td>
                                    <tr><td><label for="iSource"><?= $this->getHtml('ID'); ?></label>
                                    <tr><td><span class="input"><button type="button" formaction=""><i class="g-icon">book</i></button><input id="iSource" name="source" type="text" placeholder=""></span>
                                    <tr><td><label for="iSegment"><?= $this->getHtml('Segment'); ?></label>
                                    <tr><td><input id="iSegment" name="segment" type="text" placeholder="">
                                    <tr><td><label for="iProductgroup"><?= $this->getHtml('Productgroup'); ?></label>
                                    <tr><td><input id="iProductgroup" name="productgroup" type="text" placeholder="">
                                    <tr><td><label for="iGroup"><?= $this->getHtml('Group'); ?></label>
                                    <tr><td><input id="iGroup" name="group" type="text" placeholder="">
                                    <tr><td><label for="iArticlegroup"><?= $this->getHtml('Articlegroup'); ?></label>
                                    <tr><td><input id="iArticlegroup" name="articlegroup" type="text" placeholder="">
                                    <tr><td><label for="iPQuantity"><?= $this->getHtml('Quantity'); ?></label>
                                    <tr><td><input id="iPQuantity" name="quantity" type="text" placeholder=""><td>
                                    <tr><td><label for="iPrice"><?= $this->getHtml('Price'); ?></label>
                                    <tr><td><input id="iPrice" name="price" type="number" step="any" min="0" placeholder=""><td>
                                    <tr><td><label for="iDiscount"><?= $this->getHtml('Discount'); ?></label>
                                    <tr><td><input id="iDiscount" name="discount" type="number" step="any" min="0" placeholder=""><td>
                                    <tr><td><label for="iDiscount"><?= $this->getHtml('DiscountP'); ?></label>
                                    <tr><td><input id="iDiscountP" name="discountp" type="number" step="any" min="0" placeholder=""><td>
                                    <tr><td><label for="iBonus"><?= $this->getHtml('Bonus'); ?></label>
                                    <tr><td><input id="iBonus" name="bonus" type="number" step="any" min="0" placeholder=""><td>
                                    <tr><td><input type="submit" value="<?= $this->getHtml('Add', '0', '0'); ?>">
                                </table>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <input type="radio" id="c-tab-7" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-7' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <section class="box wf-100">
                        <header><h1><?= $this->getHtml('AreaManager'); ?></h1></header>
                        <div class="inner">
                            <form action="<?= \phpOMS\Uri\UriFactory::build('{/api}...'); ?>" method="post">
                                <table class="layout wf-100">
                                    <tbody>
                                    <tr><td><label for="iManager"><?= $this->getHtml('AreaManager'); ?></label>
                                    <tr><td><span class="input"><button type="button" formaction=""><i class="g-icon">book</i></button><input id="iManager" name="source" type="text" placeholder=""></span>
                                    <tr><td><label for="iSource"><?= $this->getHtml('ID'); ?></label>
                                    <tr><td><span class="input"><button type="button" formaction=""><i class="g-icon">book</i></button><input id="iSource" name="source" type="text" placeholder=""></span>
                                    <tr><td><label for="iSegment"><?= $this->getHtml('Segment'); ?></label>
                                    <tr><td><input id="iSegment" name="segment" type="text" placeholder="">
                                    <tr><td><label for="iProductgroup"><?= $this->getHtml('Productgroup'); ?></label>
                                    <tr><td><input id="iProductgroup" name="productgroup" type="text" placeholder="">
                                    <tr><td><label for="iGroup"><?= $this->getHtml('Group'); ?></label>
                                    <tr><td><input id="iGroup" name="group" type="text" placeholder="">
                                    <tr><td><label for="iArticlegroup"><?= $this->getHtml('Articlegroup'); ?></label>
                                    <tr><td><input id="iArticlegroup" name="articlegroup" type="text" placeholder="">
                                    <tr><td><input type="submit" value="<?= $this->getHtml('Add', '0', '0'); ?>">
                                </table>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <input type="radio" id="c-tab-8" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-8' ? ' checked' : ''; ?>>
        <div class="tab">
        </div>
        <input type="radio" id="c-tab-9" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-9' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12">
            <?php
            $footerView = new \phpOMS\Views\PaginationView($this->l11nManager, $this->request, $this->response);
            $footerView->setTemplate('/Web/Templates/Lists/Footer/PaginationBig');
            $footerView->setPages(20);
            $footerView->setPage(1);
            ?>
                    <div class="box wf-100">
                        <table class="default sticky">
                            <caption><?= $this->getHtml('Logs'); ?><i class="g-icon end-xs download btn">download</i></caption>
                            <thead>
                            <tr>
                                <td>IP
                                <td><?= $this->getHtml('ID', '0', '0'); ?>
                                <td><?= $this->getHtml('Name'); ?>
                                <td class="wf-100"><?= $this->getHtml('Log'); ?>
                                <td><?= $this->getHtml('Date'); ?>
                            <tbody>
                            <tr>
                                <td><?= $this->printHtml($this->request->getOrigin()); ?>
                                <td><?= $this->printHtml((string) $this->request->header->account); ?>
                                <td><?= $this->printHtml((string) $this->request->header->account); ?>
                                <td>Creating customer
                                <td><?= $this->printHtml((new \DateTime('now'))->format('Y-m-d H:i:s')); ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\ClientManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\Profile\Models\ContactType;
use phpOMS\Uri\UriFactory;

$countryCodes = \phpOMS\Localization\ISO3166TwoEnum::getConstants();
$countries    = \phpOMS\Localization\ISO3166NameEnum::getConstants();

/**
 * @var \Modules\ClientManagement\Models\Client $client
 */
$client = $this->getData('client');
$notes  = $client->getNotes();
$files  = $client->getFiles();

$newestInvoices    = $this->getData('newestInvoices') ?? [];
$monthlySalesCosts = $this->getData('monthlySalesCosts') ?? [];

/**
 * @var \phpOMS\Views\View $this
 */
echo $this->getData('nav')->render();
?>
<div class="tabview tab-2">
    <div class="box">
        <ul class="tab-links">
            <li><label for="c-tab-1"><?= $this->getHtml('Profile'); ?></label></li>
            <li><label for="c-tab-3"><?= $this->getHtml('Addresses'); ?></label></li>
            <li><label for="c-tab-5"><?= $this->getHtml('Payment'); ?></label></li>
            <li><label for="c-tab-6"><?= $this->getHtml('Prices'); ?></label></li>
            <li><label for="c-tab-7"><?= $this->getHtml('Attributes'); ?></label></li>
            <li><label for="c-tab-8"><?= $this->getHtml('Files'); ?></label></li>
            <li><label for="c-tab-9"><?= $this->getHtml('Invoices'); ?></label>
            <li><label for="c-tab-10"><?= $this->getHtml('Articles'); ?></label>
            <li><label for="c-tab-11"><?= $this->getHtml('Messages'); ?></label><!-- incl. support -->
            <li><label for="c-tab-11"><?= $this->getHtml('Accounting'); ?></label>
            <li><label for="c-tab-11"><?= $this->getHtml('Notes'); ?></label>
            <li><label for="c-tab-11"><?= $this->getHtml('Tags'); ?></label>
            <li><label for="c-tab-11"><?= $this->getHtml('Calendar'); ?></label>
            <li><label for="c-tab-11"><?= $this->getHtml('Permission'); ?></label>
            <li><label for="c-tab-12"><?= $this->getHtml('Logs'); ?></label>
        </ul>
    </div>
    <div class="tab-content">
        <input type="radio" id="c-tab-1" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-1' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-lg-3 last-lg">
                    <section class="portlet">
                        <form>
                            <div class="portlet-body">
                                <table class="layout wf-100">
                                    <tr><td><label for="iId"><?= $this->getHtml('ID', '0', '0'); ?></label>
                                    <tr><td><span class="input"><button type="button" formaction=""><i class="fa fa-book"></i></button><input type="number" id="iId" min="1" name="id" value="<?= $this->printHtml($client->number); ?>" disabled></span>
                                    <tr><td><label for="iName1"><?= $this->getHtml('Name1'); ?></label>
                                    <tr><td><input type="text" id="iName1" name="name1" value="<?= $this->printHtml($client->profile->account->name1); ?>" required>
                                    <tr><td><label for="iName2"><?= $this->getHtml('Name2'); ?></label>
                                    <tr><td><input type="text" id="iName2" name="name2" value="<?= $this->printHtml($client->profile->account->name2); ?>">
                                    <tr><td><label for="iName3"><?= $this->getHtml('Name3'); ?></label>
                                    <tr><td><input type="text" id="iName3" name="name3" value="<?= $this->printHtml($client->profile->account->name3); ?>">
                                </table>
                            </div>
                            <div class="portlet-foot">
                                <input type="submit" value="<?= $this->getHtml('Save', '0', '0'); ?>" name="save-client-profile"> <input type="submit" value="<?= $this->getHtml('Delete', '0', '0'); ?>" name="delete-client-profile">
                            </div>
                        </form>
                    </section>

                    <section class="portlet">
                        <div class="portlet-head">
                            <?= $this->getHtml('Contact'); ?>
                            <a class="floatRight" href=""><i class="fa fa-envelope-o btn"></i></a>
                        </div>
                        <div class="portlet-body">
                            <table class="layout wf-100">
                                <tr><td><label for="iName1"><?= $this->getHtml('Phone'); ?></label>
                                <tr><td><input type="text" id="iName1" name="name1" value="<?= $this->printHtml($client->getMainContactElement(ContactType::PHONE)->content); ?>">
                                <tr><td><label for="iName1"><?= $this->getHtml('Email'); ?></label>
                                <tr><td><input type="text" id="iName1" name="name1" value="<?= $this->printHtml($client->getMainContactElement(ContactType::EMAIL)->content); ?>">
                                <tr><td><label for="iName1"><?= $this->getHtml('Website'); ?></label>
                                <tr><td><input type="text" id="iName1" name="name1" value="<?= $this->printHtml($client->getMainContactElement(ContactType::WEBSITE)->content); ?>">
                            </table>
                        </div>
                    </section>

                    <section class="portlet">
                        <div class="portlet-head">
                            <?= $this->getHtml('Address'); ?>
                            <span class="clickPopup floatRight">
                                <label for="addressDropdown"><i class="fa fa-print btn"></i></label>
                                <input id="addressDropdown" name="addressDropdown" type="checkbox">
                                <div class="popup">
                                    <ul>
                                        <li>
                                            <input id="id1" type="checkbox">
                                            <ul>
                                                <li>
                                                    <label for="id1">
                                                        <a href="" class="button">Word</a>
                                                        <span></span>
                                                        <i class="fa fa-chevron-right expand"></i>
                                                    </label>
                                                <li>Letter
                                            </ul>
                                        <li><label class="button cancel" for="addressDropdown">Cancel</label>
                                    </ul>
                                </div>
                            </span>
                        </div>
                        <div class="portlet-body">
                            <table class="layout wf-100">
                                <?php if (!empty($client->mainAddress->addition)) : ?>
                                    <tr><td><label for="iName1"><?= $this->getHtml('Addition'); ?></label>
                                <tr><td><input type="text" id="iName1" name="name1" value="<?= $this->printHtml($client->mainAddress->addition); ?>">
                                <?php endif; ?>
                                <tr><td><label for="iName1"><?= $this->getHtml('Address'); ?></label>
                                <tr><td><input type="text" id="iName1" name="name1" value="<?= $this->printHtml($client->mainAddress->address); ?>" required>
                                <tr><td><label for="iName1"><?= $this->getHtml('Postal'); ?></label>
                                <tr><td><input type="text" id="iName1" name="name1" value="<?= $this->printHtml($client->mainAddress->postal); ?>" required>
                                <tr><td><label for="iName1"><?= $this->getHtml('City'); ?></label>
                                <tr><td><input type="text" id="iName1" name="name1" value="<?= $this->printHtml($client->mainAddress->city); ?>" required>
                                <tr><td><label for="iName1"><?= $this->getHtml('Country'); ?></label>
                                <tr><td><select name="country">
                                    <?php foreach ($countryCodes as $code3 => $code2) : ?>
                                        <option value="<?= $this->printHtml($code2); ?>"<?= $this->printHtml($code2 === $client->mainAddress->getCountry() ? ' selected' : ''); ?>><?= $this->printHtml($countries[$code3]); ?>
                                    <?php endforeach; ?>
                                </select>
                                <tr><td>
                                    <?php if (\is_file(__DIR__ . '/../../../../phpOMS/Localization/Maps/svg/' . \strtolower($client->mainAddress->getCountry()) . '.svg')) : ?>
                                    <img alt="<?= $this->getHtml('IMG_alt_map'); ?>" id="iMap" style="width: 100%;" src="<?= UriFactory::build('phpOMS/Localization/Maps/svg/' . \strtolower($client->mainAddress->getCountry()) . '.svg'); ?>">
                                    <?php endif; ?>
                            </table>
                        </div>
                    </section>

                    <section class="portlet highlight-4">
                        <div class="portlet-body">
                            <textarea class="undecorated"><?= $this->printHtml($client->info); ?></textarea>
                        </div>
                    </section>
                </div>
                <div class="col-xs-12 col-lg-9 plain-grid">
                    <div class="row">
                        <div class="col-xs-12 col-lg-4">
                            <section class="portlet highlight-1">
                                <div class="portlet-body">
                                    <table class="wf-100">
                                        <tr><td><?= $this->getHtml('YTDSales'); ?>:
                                            <td>
                                        <tr><td><?= $this->getHtml('MTDSales'); ?>:
                                            <td>
                                        <tr><td><?= $this->getHtml('CLV'); ?>:
                                            <td>
                                        <tr><td><?= $this->getHtml('MRR'); ?>:
                                            <td>
                                    </table>
                                </div>
                            </section>
                        </div>

                        <div class="col-xs-12 col-lg-4">
                            <section class="portlet highlight-2">
                                <div class="portlet-body">
                                    <table class="wf-100">
                                        <tr><td><?= $this->getHtml('LastContact'); ?>:
                                            <td>
                                        <tr><td><?= $this->getHtml('LastOrder'); ?>:
                                            <td>
                                        <tr><td><?= $this->getHtml('Created'); ?>:
                                            <td>
                                        <tr><td><?= $this->getHtml('Modified'); ?>:
                                            <td>
                                    </table>
                                </div>
                            </section>
                        </div>

                        <div class="col-xs-12 col-lg-4">
                            <section class="portlet highlight-3">
                                <div class="portlet-body">
                                    <table class="wf-100">
                                        <tr><td><?= $this->getHtml('DSO'); ?>:
                                            <td>
                                        <tr><td><?= $this->getHtml('Due'); ?>:
                                            <td>
                                        <tr><td><?= $this->getHtml('Balance'); ?>:
                                            <td>
                                        <tr><td><?= $this->getHtml('CreditRating'); ?>:
                                            <td>
                                    </table>
                                </div>
                            </section>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <section class="portlet">
                                <div class="portlet-head"><?= $this->getHtml('Notes'); ?></div>
                                <div class="slider">
                                <table id="iNotesItemList" class="default">
                                    <thead>
                                    <tr>
                                        <td class="wf-100"><?= $this->getHtml('Title'); ?>
                                        <td><?= $this->getHtml('CreatedAt'); ?>
                                    <tbody>
                                    <?php foreach ($notes as $note) :
                                        $url = UriFactory::build('{/lang}/{/app}/editor/single?{?}&id=' . $note->getId());
                                        ?>
                                    <tr data-href="<?= $url; ?>">
                                        <td><a href="<?= $url; ?>"><?= $note->title; ?></a>
                                        <td><a href="<?= $url; ?>"><?= $note->createdAt->format('Y-m-d'); ?></a>
                                    <?php endforeach; ?>
                                </table>
                                </div>
                            </section>
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <section class="portlet">
                                <div class="portlet-head"><?= $this->getHtml('Documents'); ?></div>
                                <div class="slider">
                                <table id="iFilesClientList" class="default">
                                    <thead>
                                    <tr>
                                        <td class="wf-100"><?= $this->getHtml('Title'); ?>
                                        <td>
                                        <td><?= $this->getHtml('CreatedAt'); ?>
                                    <tbody>
                                    <?php foreach ($files as $file) :
                                        $url = UriFactory::build('{/lang}/{/app}/media/single?{?}&id=' . $file->getId());
                                        ?>
                                    <tr data-href="<?= $url; ?>">
                                        <td><a href="<?= $url; ?>"><?= $file->name; ?></a>
                                        <td><a href="<?= $url; ?>"><?= $file->extension; ?></a>
                                        <td><a href="<?= $url; ?>"><?= $file->createdAt->format('Y-m-d'); ?></a>
                                    <?php endforeach; ?>
                                </table>
                                </div>
                            </section>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <section class="portlet">
                                <div class="portlet-head"><?= $this->getHtml('RecentInvoices'); ?></div>
                                <table id="iSalesItemList" class="default">
                                    <thead>
                                    <tr>
                                        <td><?= $this->getHtml('Number'); ?>
                                        <td><?= $this->getHtml('Type'); ?>
                                        <td class="wf-100"><?= $this->getHtml('Name'); ?>
                                        <td><?= $this->getHtml('Net'); ?>
                                        <td><?= $this->getHtml('Date'); ?>
                                    <tbody>
                                    <?php
                                    /** @var \Modules\Billing\Models\Bill $invoice */
                                    foreach ($newestInvoices as $invoice) :
                                        $url = UriFactory::build('{/lang}/{/app}/sales/bill?{?}&id=' . $invoice->getId());
                                        ?>
                                    <tr data-href="<?= $url; ?>">
                                        <td><a href="<?= $url; ?>"><?= $invoice->getNumber(); ?></a>
                                        <td><a href="<?= $url; ?>"><?= $invoice->type->getL11n(); ?></a>
                                        <td><a href="<?= $url; ?>"><?= $invoice->billTo; ?></a>
                                        <td><a href="<?= $url; ?>"><?= $invoice->netSales->getCurrency(); ?></a>
                                        <td><a href="<?= $url; ?>"><?= $invoice->createdAt->format('Y-m-d'); ?></a>
                                    <?php endforeach; ?>
                                </table>
                            </section>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <section class="portlet">
                                <div class="portlet-head"><?= $this->getHtml('Segments'); ?></div>
                                <div class="portlet-body"></div>
                            </section>
                        </div>

                        <div class="col-xs-12 col-lg-6">
                            <section class="portlet">
                                <div class="portlet-head"><?= $this->getHtml('Sales'); ?></div>
                                <div class="portlet-body">
                                    <canvas id="sales-region" data-chart='{
                                            "type": "bar",
                                            "data": {
                                                "labels": [
                                                    <?php
                                                        $temp = [];
                                                        foreach ($monthlySalesCosts as $monthly) {
                                                            $temp[] = $monthly['month'] . '/' . \substr((string) $monthly['year'], -2);
                                                        }
                                                    ?>
                                                    <?= '"' . \implode('", "', $temp) . '"'; ?>
                                                ],
                                                "datasets": [
                                                    {
                                                        "label": "<?= $this->getHtml('Margin'); ?>",
                                                        "type": "line",
                                                        "data": [
                                                            <?php
                                                                $temp = [];
                                                                foreach ($monthlySalesCosts as $monthly) {
                                                                    $temp[] = \round(((((int) $monthly['net_sales']) - ((int) $monthly['net_costs'])) / ((int) $monthly['net_sales'])) * 100, 2);
                                                                }
                                                            ?>
                                                            <?= \implode(',', $temp); ?>
                                                        ],
                                                        "yAxisID": "axis-2",
                                                        "fill": false,
                                                        "borderColor": "rgb(255, 99, 132)",
                                                        "backgroundColor": "rgb(255, 99, 132)"
                                                    },
                                                    {
                                                        "label": "<?= $this->getHtml('Sales'); ?>",
                                                        "type": "bar",
                                                        "data": [
                                                            <?php
                                                                $temp = [];
                                                                foreach ($monthlySalesCosts as $monthly) {
                                                                    $temp[] = ((int) $monthly['net_sales']) / 1000;
                                                                }
                                                            ?>
                                                            <?= \implode(',', $temp); ?>
                                                        ],
                                                        "yAxisID": "axis-1",
                                                        "backgroundColor": "rgb(54, 162, 235)"
                                                    }
                                                ]
                                            },
                                            "options": {
                                                "scales": {
                                                    "yAxes": [
                                                        {
                                                            "id": "axis-1",
                                                            "display": true,
                                                            "position": "left"
                                                        },
                                                        {
                                                            "id": "axis-2",
                                                            "display": true,
                                                            "position": "right",
                                                            "scaleLabel": {
                                                                "display": true,
                                                                "labelString": "<?= $this->getHtml('Margin'); ?> %"
                                                            },
                                                            "gridLines": {
                                                                "display": false
                                                            },
                                                            "beginAtZero": true,
                                                            "ticks": {
                                                                "min": 0,
                                                                "max": 100,
                                                                "stepSize": 10
                                                            }
                                                        }
                                                    ]
                                                }
                                            }
                                    }'></canvas>
                                </div>
                            </section>
                        </div>
                    </div>
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

            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <section class="box wf-100">
                        <header><h1><?= $this->getHtml('PaymentTerm'); ?></h1></header>
                        <div class="inner">
                            <form>
                                <table class="layout wf-100">
                                    <tr><td><label for="iSource"><?= $this->getHtml('ID'); ?></label>
                                    <tr><td><span class="input"><button type="button" formaction=""><i class="fa fa-book"></i></button><input id="iSource" name="source" type="text" placeholder=""></span>
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
        <input type="radio" id="c-tab-6" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-6' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <section class="box wf-100">
                        <header><h1><?= $this->getHtml('Price'); ?></h1></header>
                        <div class="inner">
                            <form action="<?= UriFactory::build('{/api}...'); ?>" method="post">
                                <table class="layout wf-100">
                                    <tbody>
                                    <tr><td colspan="2"><label for="iPType"><?= $this->getHtml('Type'); ?></label>
                                    <tr><td><select id="iPType" name="ptye">
                                                <option>
                                            </select><td>
                                    <tr><td><label for="iSource"><?= $this->getHtml('ID'); ?></label>
                                    <tr><td><span class="input"><button type="button" formaction=""><i class="fa fa-book"></i></button><input id="iSource" name="source" type="text" placeholder=""></span>
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
                            <form action="<?= UriFactory::build('{/api}...'); ?>" method="post">
                                <table class="layout wf-100">
                                    <tbody>
                                    <tr><td><label for="iManager"><?= $this->getHtml('AreaManager'); ?></label>
                                    <tr><td><span class="input"><button type="button" formaction=""><i class="fa fa-book"></i></button><input id="iManager" name="source" type="text" placeholder=""></span>
                                    <tr><td><label for="iSource"><?= $this->getHtml('ID'); ?></label>
                                    <tr><td><span class="input"><button type="button" formaction=""><i class="fa fa-book"></i></button><input id="iSource" name="source" type="text" placeholder=""></span>
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
            <?php include __DIR__ . '/client-profile-bills.tpl.php'; ?>
        </div>
        <input type="radio" id="c-tab-10" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-9' ? ' checked' : ''; ?>>
        <div class="tab">
            <?php include __DIR__ . '/client-profile-items.tpl.php'; ?>
        </div>
        <input type="radio" id="c-tab-11" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-10' ? ' checked' : ''; ?>>
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
                        <table class="default">
                            <caption><?= $this->getHtml('Logs'); ?><i class="fa fa-download floatRight download btn"></i></caption>
                            <thead>
                            <tr>
                                <td>IP
                                <td><?= $this->getHtml('ID', '0', '0'); ?>
                                <td><?= $this->getHtml('Name'); ?>
                                <td class="wf-100"><?= $this->getHtml('Log'); ?>
                                <td><?= $this->getHtml('Date'); ?>
                            <tfoot>
                            <tr>
                                <td colspan="6">
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

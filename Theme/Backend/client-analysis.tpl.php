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

use phpOMS\Localization\Money;

/* @todo: single month/quarter/fiscal year/calendar year */
/* @todo: time range (<= 12 month = monthly view; else annual view/comparison) */

/**
 * @var \phpOMS\Views\View $this
 */
echo $this->getData('nav')->render();
?>

<div class="tabview tab-2">
    <div class="box">
        <ul class="tab-links">
            <li><label for="c-tab-1"><?= $this->getHtml('Analysis'); ?></label></li>
            <li><label for="c-tab-2"><?= $this->getHtml('Customers'); ?></label></li>
            <li><label for="c-tab-3"><?= $this->getHtml('NewCustomers'); ?></label></li>
            <li><label for="c-tab-4"><?= $this->getHtml('LostCustomers'); ?></label></li>
            <li><label for="c-tab-5"><?= $this->getHtml('Margins'); ?></label></li>
        </ul>
    </div>
    <div class="tab-content">
        <input type="radio" id="c-tab-1" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-1' ? ' checked' : ''; ?>>
        <div class="tab">
        	<div class="row">
                <div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<div class="portlet-head"><?= $this->getHtml('Filter'); ?></div>
                    	<form>
                            <div class="portlet-body">
                                <table class="layout wf-100">
                                    <tr><td><label for="iId"><?= $this->getHtml('Client'); ?></label>
                                    <tr><td><input type="text" id="iName1" name="name1" required>
                                </table>
                            </div>
                        </form>
        			</section>
        		</div>
        	</div>
        </div>

        <input type="radio" id="c-tab-2" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-2' ? ' checked' : ''; ?>>
        <div class="tab">
        	<div class="row">
                <div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<div class="portlet-head">
                    		Sales / Customers
                    		<?php include __DIR__ . '/../../../../Web/Backend/Themes/popup-export-data.tpl.php'; ?>
                    	</div>
                        <?php $salesCustomer = $this->getData('salesCustomer'); ?>
                        <div class="portlet-body">
                        	<canvas id="sales-region" data-chart='{
                                            "type": "bar",
                                            "data": {
                                                "labels": [
                                                    <?php
                                                        $temp = [];
                                                        foreach ($salesCustomer as $monthly) {
                                                            $temp[] = $monthly['month'] . '/' . \substr((string) $monthly['year'], -2);
                                                        }
                                                    ?>
                                                    <?= '"' . \implode('", "', $temp) . '"'; ?>
                                                ],
                                                "datasets": [
                                                    {
                                                        "label": "<?= $this->getHtml('Customers'); ?>",
                                                        "type": "line",
                                                        "data": [
                                                            <?php
                                                                $temp = [];
                                                                foreach ($salesCustomer as $monthly) {
                                                                    $temp[] = ((int) $monthly['customers']);
                                                                }
                                                            ?>
                                                            <?= \implode(',', $temp); ?>
                                                        ],
                                                        "yAxisID": "axis-2",
                                                        "fill": false,
                                                        "borderColor": "rgb(255, 99, 132)",
                                                        "backgroundColor": "rgb(255, 99, 132)",
                                                        "tension": 0.0
                                                    },
                                                    {
                                                        "label": "<?= $this->getHtml('Sales'); ?>",
                                                        "type": "bar",
                                                        "data": [
                                                            <?php
                                                                $temp = [];
                                                                foreach ($salesCustomer as $monthly) {
                                                                    $temp[] = ((int) $monthly['net_sales']) / 1000;
                                                                }
                                                            ?>
                                                            <?= \implode(',', $temp); ?>
                                                        ],
                                                        "yAxisID": "axis-1",
                                                        "fill": false,
                                                        "borderColor": "rgb(54, 162, 235)",
                                                        "backgroundColor": "rgb(54, 162, 235)",
                                                        "tension": 0.0
                                                    }
                                                ]
                                            },
                                            "options": {
											    "title": {
												    "display": false,
												    "text": "Sales / Customers"
											    },
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
                                                                "labelString": "<?= $this->getHtml('Customers'); ?>"
                                                            },
                                                            "gridLines": {
                                                                "display": false
                                                            }
                                                        }
                                                    ]
                                                }
                                            }
                                    }'></canvas>
	                        <div class="more-container">
	                        	<input id="more-customer-sales" type="checkbox">
	                        	<label for="more-customer-sales">
	                        		<span>Data</span>
	                        		<i class="fa fa-chevron-right expand"></i>
	                        	</label>
	                        	<div>
	                            <table class="default">
	                            	<thead>
	                            		<tr>
	                            			<td>Month
	                            			<td>Sales
	                            			<td>Customer count
	                        		<tbody>
	                            		<?php
	                            			$sum1 = 0;
	                            			$sum2 = 0;
	                            		foreach ($salesCustomer as $values) :
	                            			$sum1 += ((int) $values['net_sales']) / 1000;
	                            			$sum2 += ((int) $values['customers']);
	                            		?>
	                            			<tr>
	                            				<td><?= $values['month'] . '/' . \substr((string) $values['year'], -2) ?>
	                            				<td><?= (new Money(((int) $values['net_sales']) / 1000))->getCurrency(); ?>
	                            				<td><?= ((int) $values['customers']); ?>
	                            		<?php endforeach; ?>
	                            			<tr>
	                            				<td>Total
	                            				<td><?= (new Money($sum1))->getCurrency(); ?>
	                            				<td><?= (int) ($sum2 / 12); ?>
	                            </table>
	                        	</div>
	                        </div>
                    	</div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<?php $customerRetention = $this->getData('customerRetention'); ?>
                        <div class="portlet-body">
                        	<canvas id="sales-region" data-chart='{
                                            "type": "bar",
                                            "data": {
                                                "labels": [
                                                    <?php
                                                        $temp = [];
                                                        foreach ($customerRetention as $monthly) {
                                                            $temp[] = (string) $monthly['year'];
                                                        }
                                                    ?>
                                                    <?= '"' . \implode('", "', $temp) . '"'; ?>
                                                ],
                                                "datasets": [
                                                    {
                                                        "label": "<?= $this->getHtml('Retention'); ?>",
                                                        "type": "line",
                                                        "data": [
                                                            <?php
                                                                $temp = [];
                                                                foreach ($customerRetention as $monthly) {
                                                                    $temp[] = ((int) $monthly['customers']);
                                                                }
                                                            ?>
                                                            <?= \implode(',', $temp); ?>
                                                        ],
                                                        "yAxisID": "axis-1",
                                                        "fill": false,
                                                        "borderColor": "rgb(54, 162, 235)",
                                                        "backgroundColor": "rgb(54, 162, 235)",
                                                        "tension": 0.0
                                                    }
                                                ]
                                            },
                                            "options": {
											    "title": {
												    "display": true,
												    "text": "Customer retention"
											    },
                                                "scales": {
                                                    "yAxes": [
                                                        {
                                                            "id": "axis-1",
                                                            "display": true,
                                                            "position": "left"
                                                        }
                                                    ]
                                                }
                                            }
                                    }'></canvas>

                            <table class="default">
                            	<thead>
                            		<tr>
                            			<td>Year
                            			<td>Retention
                        		<tbody>
                            		<?php
                            			$sum1 = 0;
                            		foreach ($customerRetention as $values) :
                            			$sum1 += ((int) $values['customers']);
                            		?>
                            			<tr>
                            				<td><?= \substr((string) $values['year'], -2) ?>
                            				<td><?= ((int) $values['customers']); ?>
                            		<?php endforeach; ?>
                            			<tr>
                            				<td>Avg.
                            				<td><?= $sum1 / 12; ?>
                            </table>
                        </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<?php $customerRegion = $this->getData('customerRegion'); ?>
                        <div class="portlet-body">
                        	<canvas id="sales-region" data-chart='{
                                        "type": "pie",
                                        "data": {
                                            "labels": [
                                                    "Europe", "America", "Asia", "Africa", "CIS", "Other"
                                                ],
                                            "datasets": [{
                                                "data": [
                                                    <?= (int) ($customerRegion['Europe'] ?? 0); ?>,
                                                    <?= (int) ($customerRegion['America'] ?? 0); ?>,
                                                    <?= (int) ($customerRegion['Asia'] ?? 0); ?>,
                                                    <?= (int) ($customerRegion['Africa'] ?? 0); ?>,
                                                    <?= (int) ($customerRegion['CIS'] ?? 0); ?>,
                                                    <?= (int) ($customerRegion['Other'] ?? 0); ?>
                                                ],
                                                "backgroundColor": [
                                                    "rgb(255, 99, 132)",
                                                    "rgb(255, 159, 64)",
                                                    "rgb(255, 205, 86)",
                                                    "rgb(75, 192, 192)",
                                                    "rgb(54, 162, 235)",
                                                    "rgb(153, 102, 255)"
                                                ]
                                            }]
                                        },
                                        "options": {
										    "title": {
											    "display": true,
											    "text": "Customers per region"
										    }
										}
                                }'></canvas>
                            <table class="default">
                            	<thead>
                            		<tr>
                            			<td>Region
                            			<td>Customer count
                        		<tbody>
                            		<?php
                            			$sum = 0;
                            		foreach ($customerRegion as $region => $values) : $sum += $values; ?>
                            			<tr>
                            				<td><?= $region; ?>
                            				<td><?= $values; ?>
                            		<?php endforeach; ?>
                            			<tr>
                            				<td>Total
                            				<td><?= $sum; ?>
                            </table>
                        </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<?php $customersRep = $this->getData('customersRep'); ?>
                        <div class="portlet-body">
                        	<canvas id="sales-region" data-chart='{
                                            "type": "horizontalBar",
                                            "data": {
                                                "labels": [
                                                    <?php
                                                        $temp = [];
                                                        foreach ($customersRep as $name => $rep) {
                                                            $temp[] = $name;
                                                        }
                                                    ?>
                                                    <?= '"' . \implode('", "', $temp) . '"'; ?>
                                                ],
                                                "datasets": [
                                                    {
                                                        "label": "<?= $this->getHtml('Customers'); ?>",
                                                        "type": "horizontalBar",
                                                        "data": [
                                                            <?php
                                                                $temp = [];
                                                                foreach ($customersRep as $values) {
                                                                    $temp[] = ((int) $values['customers']);
                                                                }
                                                            ?>
                                                            <?= \implode(',', $temp); ?>
                                                        ],
                                                        "fill": false,
                                                        "borderColor": "rgb(54, 162, 235)",
                                                        "backgroundColor": "rgb(54, 162, 235)",
                                                        "tension": 0.0
                                                    }
                                                ]
                                            },
                                            "options": {
											    "title": {
												    "display": true,
												    "text": "Customers per rep"
											    }
                                            }
                                    }'></canvas>
                            <table class="default">
                            	<thead>
                            		<tr>
                            			<td>Rep
                            			<td>Customer count
                        		<tbody>
                            		<?php
                            			$sum = 0;
                            		foreach ($customersRep as $rep => $values) : $sum += $values['customers']; ?>
                            			<tr>
                            				<td><?= $rep; ?>
                            				<td><?= $values['customers']; ?>
                            		<?php endforeach; ?>
                            			<tr>
                            				<td>Total
                            				<td><?= $sum; ?>
                            </table>
                        </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<?php $customersCountry = $this->getData('customersCountry'); ?>
                        <div class="portlet-body">
                        	<canvas id="sales-region" data-chart='{
                                            "type": "horizontalBar",
                                            "data": {
                                                "labels": [
                                                    <?php
                                                        $temp = [];
                                                        foreach ($customersCountry as $name => $country) {
                                                            $temp[] = $name;
                                                        }
                                                    ?>
                                                    <?= '"' . \implode('", "', $temp) . '"'; ?>
                                                ],
                                                "datasets": [
                                                    {
                                                        "label": "<?= $this->getHtml('Customers'); ?>",
                                                        "type": "horizontalBar",
                                                        "data": [
                                                            <?php
                                                                $temp = [];
                                                                foreach ($customersCountry as $values) {
                                                                    $temp[] = ((int) $values['customers']);
                                                                }
                                                            ?>
                                                            <?= \implode(',', $temp); ?>
                                                        ],
                                                        "fill": false,
                                                        "borderColor": "rgb(54, 162, 235)",
                                                        "backgroundColor": "rgb(54, 162, 235)",
                                                        "tension": 0.0
                                                    }
                                                ]
                                            },
                                            "options": {
											    "title": {
												    "display": true,
												    "text": "Customers per country"
											    }
                                            }
                                    }'></canvas>

                            <table class="default">
                            	<thead>
                            		<tr>
                            			<td>Country
                            			<td>Customer count
                        		<tbody>
                            		<?php
                            			$sum = 0;
                            		foreach ($customersCountry as $country => $values) : $sum += $values['customers']; ?>
                            			<tr>
                            				<td><?= $country; ?>
                            				<td><?= $values['customers']; ?>
                            		<?php endforeach; ?>
                            			<tr>
                            				<td>Total
                            				<td><?= $sum; ?>
                            </table>
                            </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<?php $customerGroups = $this->getData('customerGroups'); ?>
                        <div class="portlet-body">
                        	<canvas id="sales-region" data-chart='{
                                        "type": "pie",
                                        "data": {
                                            "labels": [
                                                    <?php
                                                        $temp = [];
                                                        foreach ($customerGroups as $name => $groups) {
                                                            $temp[] = $name;
                                                        }
                                                    ?>
                                                    <?= '"' . \implode('", "', $temp) . '"'; ?>
                                                ],
                                            "datasets": [{
                                                "data": [
                                                    <?php
                                                        $temp = [];
                                                        foreach ($customerGroups as $values) {
                                                            $temp[] = ((int) $values['customers']);
                                                        }
                                                    ?>
                                                    <?= \implode(',', $temp); ?>
                                                ],
                                                "backgroundColor": [
                                                    "rgb(255, 99, 132)",
                                                    "rgb(255, 159, 64)",
                                                    "rgb(255, 205, 86)",
                                                    "rgb(75, 192, 192)",
                                                    "rgb(54, 162, 235)",
                                                    "rgb(153, 102, 255)"
                                                ]
                                            }]
                                        },
                                        "options": {
										    "title": {
											    "display": true,
											    "text": "Customers per group"
										    }
										}
                                }'></canvas>

                            <table class="default">
                            	<thead>
                            		<tr>
                            			<td>Groups
                            			<td>Customer count
                        		<tbody>
                            		<?php
                            			$sum = 0;
                            		foreach ($customerGroups as $groups => $values) : $sum += $values['customers']; ?>
                            			<tr>
                            				<td><?= $groups; ?>
                            				<td><?= $values['customers']; ?>
                            		<?php endforeach; ?>
                            			<tr>
                            				<td>Total
                            				<td><?= $sum; ?>
                            </table>
                        </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<?php $salesRegion = $this->getData('salesRegion'); ?>
                        <div class="portlet-body">
                        	<canvas id="sales-region" data-chart='{
                                        "type": "pie",
                                        "data": {
                                            "labels": [
                                                    "Europe", "America", "Asia", "Africa", "CIS", "Other"
                                                ],
                                            "datasets": [{
                                                "data": [
                                                    <?= (int) ($salesRegion['Europe'] ?? 0); ?>,
                                                    <?= (int) ($salesRegion['America'] ?? 0); ?>,
                                                    <?= (int) ($salesRegion['Asia'] ?? 0); ?>,
                                                    <?= (int) ($salesRegion['Africa'] ?? 0); ?>,
                                                    <?= (int) ($salesRegion['CIS'] ?? 0); ?>,
                                                    <?= (int) ($salesRegion['Other'] ?? 0); ?>
                                                ],
                                                "backgroundColor": [
                                                    "rgb(255, 99, 132)",
                                                    "rgb(255, 159, 64)",
                                                    "rgb(255, 205, 86)",
                                                    "rgb(75, 192, 192)",
                                                    "rgb(54, 162, 235)",
                                                    "rgb(153, 102, 255)"
                                                ]
                                            }]
                                        },
                                        "options": {
										    "title": {
											    "display": true,
											    "text": "Sales per region"
										    }
										}
                                }'></canvas>
                        </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<?php $salesCountry = $this->getData('salesCountry'); ?>
                        <div class="portlet-body">
                        	<canvas id="sales-region" data-chart='{
                                            "type": "horizontalBar",
                                            "data": {
                                                "labels": [
                                                    <?php
                                                        $temp = [];
                                                        foreach ($salesCountry as $name => $country) {
                                                            $temp[] = $name;
                                                        }
                                                    ?>
                                                    <?= '"' . \implode('", "', $temp) . '"'; ?>
                                                ],
                                                "datasets": [
                                                    {
                                                        "label": "<?= $this->getHtml('Sales'); ?>",
                                                        "type": "horizontalBar",
                                                        "data": [
                                                            <?php
                                                                $temp = [];
                                                                foreach ($salesCountry as $values) {
                                                                    $temp[] = ((int) $values['net_sales']);
                                                                }
                                                            ?>
                                                            <?= \implode(',', $temp); ?>
                                                        ],
                                                        "fill": false,
                                                        "borderColor": "rgb(54, 162, 235)",
                                                        "backgroundColor": "rgb(54, 162, 235)",
                                                        "tension": 0.0
                                                    }
                                                ]
                                            },
                                            "options": {
											    "title": {
												    "display": true,
												    "text": "Sales per country"
											    }
                                            }
                                    }'></canvas>
                            </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<?php $salesGroups = $this->getData('salesGroups'); ?>
                        <div class="portlet-body">
                        	<canvas id="sales-region" data-chart='{
                                        "type": "pie",
                                        "data": {
                                            "labels": [
                                                    <?php
                                                        $temp = [];
                                                        foreach ($salesGroups as $name => $groups) {
                                                            $temp[] = $name;
                                                        }
                                                    ?>
                                                    <?= '"' . \implode('", "', $temp) . '"'; ?>
                                                ],
                                            "datasets": [{
                                                "data": [
                                                    <?php
                                                        $temp = [];
                                                        foreach ($salesGroups as $values) {
                                                            $temp[] = ((int) $values['net_sales']) / 1000;
                                                        }
                                                    ?>
                                                    <?= \implode(',', $temp); ?>
                                                ],
                                                "backgroundColor": [
                                                    "rgb(255, 99, 132)",
                                                    "rgb(255, 159, 64)",
                                                    "rgb(255, 205, 86)",
                                                    "rgb(75, 192, 192)",
                                                    "rgb(54, 162, 235)",
                                                    "rgb(153, 102, 255)"
                                                ]
                                            }]
                                        },
                                        "options": {
										    "title": {
											    "display": true,
											    "text": "Sales per group"
										    }
										}
                                }'></canvas>
                        </div>
        			</section>
        		</div>
        	</div>
        </div>

        <input type="radio" id="c-tab-3" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-3' ? ' checked' : ''; ?>>
        <div class="tab">
        	<div class="row">
                <div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<div class="portlet-head">New customers</div>
                        <div class="portlet-body">
                        	Shows new customers and their sales
                        </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<div class="portlet-head">New customers per region</div>
                        <div class="portlet-body">
                        </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<div class="portlet-head">New customers per sales rep</div>
                        <div class="portlet-body">
                        </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<div class="portlet-head">New customers per sales group</div>
                        <div class="portlet-body">
                        </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<div class="portlet-head">New customers per customer group</div>
                        <div class="portlet-body">
                        </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<div class="portlet-head">New customers sales per customer group</div>
                        <div class="portlet-body">
                        </div>
        			</section>
        		</div>
        	</div>
        </div>

        <input type="radio" id="c-tab-4" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-4' ? ' checked' : ''; ?>>
        <div class="tab">
        	<div class="row">
                <div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<div class="portlet-head">Lost customers</div>
                        <div class="portlet-body">
                        	Shows lost customers and their sales
                        </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<div class="portlet-head">Lost customers per region</div>
                        <div class="portlet-body">
                        </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<div class="portlet-head">Lost customers per sales rep</div>
                        <div class="portlet-body">
                        </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<div class="portlet-head">Lost customers per sales group</div>
                        <div class="portlet-body">
                        </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<div class="portlet-head">Lost customers per customer group</div>
                        <div class="portlet-body">
                        </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<div class="portlet-head">Lost customers sales per customer group</div>
                        <div class="portlet-body">
                        </div>
        			</section>
        		</div>
        	</div>
        </div>

        <input type="radio" id="c-tab-5" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-5' ? ' checked' : ''; ?>>
        <div class="tab">
        	<div class="row">
                <div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<?php $monthlySalesCosts = $this->getData('monthlySalesCosts'); ?>
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
                                                        "backgroundColor": "rgb(255, 99, 132)",
                                                        "tension": 0.0
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
											    "title": {
												    "display": true,
												    "text": "Sales / Margin"
											    },
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

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<div class="portlet-head">Margins per region</div>
                        <div class="portlet-body">
                        </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<div class="portlet-head">Margins per sales rep</div>
                        <div class="portlet-body">
                        </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<div class="portlet-head">Margins per sales group</div>
                        <div class="portlet-body">
                        </div>
        			</section>
        		</div>

        		<div class="col-xs-12 col-lg-6">
                    <section class="portlet">
                    	<div class="portlet-head">Margins per customer group</div>
                        <div class="portlet-body">
                        </div>
        			</section>
        		</div>
        	</div>
        </div>
    </div>
</div>
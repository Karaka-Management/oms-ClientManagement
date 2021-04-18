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

namespace Modules\ClientManagement\Controller;

use Modules\Billing\Models\BillTypeL11n;
use Modules\Billing\Models\SalesBillMapper;
use Modules\ClientManagement\Models\ClientMapper;
use Modules\Media\Models\Media;
use phpOMS\Asset\AssetType;
use phpOMS\Contract\RenderableInterface;
use phpOMS\Localization\Money;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Stdlib\Base\SmartDateTime;
use phpOMS\Views\View;
use phpOMS\Localization\ISO3166NameEnum;

/**
 * ClientManagement class.
 *
 * @package Modules\ClientManagement
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class BackendController extends Controller
{
    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewClientManagementClientList(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/ClientManagement/Theme/Backend/client-list');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1003102001, $request, $response));

        $client = ClientMapper::with('notes', models: null)
            ::with('contactElements', models: null)
            //::with('type', 'backend_image', models: [Media::class]) // @todo: it would be nicer if I coult say files:type or files/type and remove the models parameter? @todo: uncommented for now because the type is also part of client and therefore bug. that's the problem with a mix of black/whitelisting in the datamapper with the "with" feature. make it whitelist only for belongsTo, ownsMany, hasOne, ....
            ::getAfterPivot(0, null, 25);

        $view->addData('client', $client);

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewClientManagementClientCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/ClientManagement/Theme/Backend/client-create');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1003102001, $request, $response));

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewClientManagementClientProfile(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $head = $response->get('Content')->getData('head');
        $head->addAsset(AssetType::CSS, 'Resources/chartjs/Chartjs/chart.css');
        $head->addAsset(AssetType::JSLATE, 'Resources/chartjs/Chartjs/chart.js');
        $head->addAsset(AssetType::JSLATE, 'Modules/ClientManagement/Controller.js', ['type' => 'module']);

        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/ClientManagement/Theme/Backend/client-profile');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1003102001, $request, $response));

        $client = ClientMapper
            ::with('files', limit: 5, orderBy: 'createdAt', sortOrder: 'ASC')
            ::with('notes', limit: 5, orderBy: 'id', sortOrder: 'ASC')
            ::get((int) $request->getData('id'));

        $view->setData('client', $client);

        // stats
        if ($this->app->moduleManager->isActive('Billing')) {
            $ytd               = SalesBillMapper::getSalesByClientId($client->getId(), new SmartDateTime('Y-01-01'), new SmartDateTime('now'));
            $mtd               = SalesBillMapper::getSalesByClientId($client->getId(), new SmartDateTime('Y-m-01'), new SmartDateTime('now'));
            $lastOrder         = SalesBillMapper::getLastOrderDateByClientId($client->getId());
            $newestInvoices    = SalesBillMapper
                ::with('language', $response->getLanguage(), [BillTypeL11n::class])
                ::getNewestClientInvoices($client->getId(), 5);
            $monthlySalesCosts = SalesBillMapper::getClientMonthlySalesCosts($client->getId(), (new SmartDateTime('now'))->createModify(-1), new SmartDateTime('now'));
            $items             = SalesBillMapper::getClientItem($client->getId(), (new SmartDateTime('now'))->createModify(-1), new SmartDateTime('now'));
        } else {
            $ytd               = new Money();
            $mtd               = new Money();
            $lastOrder         = null;
            $newestInvoices    = [];
            $monthlySalesCosts = [];
            $items             = [];
        }

        $view->addData('ytd', $ytd);
        $view->addData('mtd', $mtd);
        $view->addData('lastOrder', $lastOrder);
        $view->addData('newestInvoices', $newestInvoices);
        $view->addData('monthlySalesCosts', $monthlySalesCosts);
        $view->addData('items', $items);

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewClientManagementClientAnalysis(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewClientAnalysis(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $head = $response->get('Content')->getData('head');
        $head->addAsset(AssetType::CSS, 'Resources/chartjs/Chartjs/chart.css');
        $head->addAsset(AssetType::JSLATE, 'Resources/chartjs/Chartjs/chart.js');
        $head->addAsset(AssetType::JSLATE, 'Modules/ClientManagement/Controller.js', ['type' => 'module']);

        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/ClientManagement/Theme/Backend/client-analysis');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1001602001, $request, $response));

        //
        $monthlySalesCosts = [];
        for ($i = 1; $i < 13; ++$i) {
            $monthlySalesCosts[] = [
                'net_sales' => $sales = \mt_rand(1200000000, 2000000000),
                'net_costs' => (int) ($sales * \mt_rand(25, 55) / 100),
                'year' => 2020,
                'month' => $i,
            ];
        }

        $view->addData('monthlySalesCosts', $monthlySalesCosts);

        //
        $salesCustomer = [];
        for ($i = 1; $i < 13; ++$i) {
            $salesCustomer[] = [
                'net_sales' => $sales = \mt_rand(1200000000, 2000000000),
                'customers' => \mt_rand(200, 400),
                'year' => 2020,
                'month' => $i,
            ];
        }

        $view->addData('salesCustomer', $salesCustomer);

        //
        $customerRetention = [];
        for ($i = 1; $i < 10; ++$i) {
            $customerRetention[] = [
                'customers' => \mt_rand(200, 400),
                'year' => \date('y') - 9 + $i,
            ];
        }

        $view->addData('customerRetention', $customerRetention);

        //
        $customerRegion = [
            'Europe' => (int) (\mt_rand(200, 400) / 4),
            'America' => (int) (\mt_rand(200, 400) / 4),
            'Asia' => (int) (\mt_rand(200, 400) / 4),
            'Africa' => (int) (\mt_rand(200, 400) / 4),
            'CIS' => (int) (\mt_rand(200, 400) / 4),
            'Other' => (int) (\mt_rand(200, 400) / 4),
        ];

        $view->addData('customerRegion', $customerRegion);

        //
        $customersRep = [];
        for ($i = 1; $i < 13; ++$i) {
            $customersRep['Rep ' . $i] = [
                'customers' => (int) (\mt_rand(200, 400) / 12),
            ];
        }

        \uasort($customersRep, function($a, $b) { return $b['customers'] <=> $a['customers']; });

        $view->addData('customersRep', $customersRep);

        //
        $customersCountry = [];
        for ($i = 1; $i < 13; ++$i) {
            $country = ISO3166NameEnum::getRandom();
            $customersCountry[\substr($country, 0, 20)] = [
                'customers' => (int) (\mt_rand(200, 400) / 12),
            ];
        }

        \uasort($customersCountry, function($a, $b) { return $b['customers'] <=> $a['customers']; });

        $view->addData('customersCountry', $customersCountry);

        //
        $customerGroups = [];
        for ($i = 1; $i < 7; ++$i) {
            $customerGroups['Group ' . $i] = [
                'customers' => (int) (\mt_rand(200, 400) / 12),
            ];
        }

        $view->addData('customerGroups', $customerGroups);

        //
        $salesRegion = [
            'Europe' => (int) (\mt_rand(1200000000, 2000000000) / 4),
            'America' => (int) (\mt_rand(1200000000, 2000000000) / 4),
            'Asia' => (int) (\mt_rand(1200000000, 2000000000) / 4),
            'Africa' => (int) (\mt_rand(1200000000, 2000000000) / 4),
            'CIS' => (int) (\mt_rand(1200000000, 2000000000) / 4),
            'Other' => (int) (\mt_rand(1200000000, 2000000000) / 4),
        ];

        $view->addData('salesRegion', $salesRegion);

        //
        $salesRep = [];
        for ($i = 1; $i < 13; ++$i) {
            $salesRep['Rep ' . $i] = [
                'net_sales' => (int) (\mt_rand(1200000000, 2000000000) / 12),
            ];
        }

        \uasort($salesRep, function($a, $b) { return $b['net_sales'] <=> $a['net_sales']; });

        $view->addData('salesRep', $salesRep);

        //
        $salesCountry = [];
        for ($i = 1; $i < 13; ++$i) {
            $country = ISO3166NameEnum::getRandom();
            $salesCountry[\substr($country, 0, 20)] = [
                'net_sales' => (int) (\mt_rand(1200000000, 2000000000) / 12),
            ];
        }

        \uasort($salesCountry, function($a, $b) { return $b['net_sales'] <=> $a['net_sales']; });

        $view->addData('salesCountry', $salesCountry);

        //
        $salesGroups = [];
        for ($i = 1; $i < 7; ++$i) {
            $salesGroups['Group ' . $i] = [
                'net_sales' => (int) (\mt_rand(1200000000, 2000000000) / 12),
            ];
        }

        $view->addData('salesGroups', $salesGroups);

        return $view;
    }
}

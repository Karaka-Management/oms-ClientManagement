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

namespace Modules\ClientManagement\Controller;

use Modules\Billing\Models\SalesBillMapper;
use Modules\ClientManagement\Models\ClientMapper;
use phpOMS\Asset\AssetType;
use phpOMS\Contract\RenderableInterface;
use phpOMS\DataStorage\Database\Query\OrderType;
use phpOMS\Localization\ISO3166CharEnum;
use phpOMS\Localization\ISO3166NameEnum;
use phpOMS\Localization\Money;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Stdlib\Base\SmartDateTime;
use phpOMS\Views\View;

/**
 * ClientManagement class.
 *
 * @package Modules\ClientManagement
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 * @codeCoverageIgnore
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
    public function viewClientManagementClientList(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/ClientManagement/Theme/Backend/client-list');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1003102001, $request, $response));

        /** @var \Modules\ClientManagement\Models\Client $client */
        $client = ClientMapper::getAll()
            ->with('profile')
            ->with('profile/account')
            ->with('files')
            ->with('files/type')
            ->with('mainAddress')
            ->where('files/type/name', 'client_profile_image')
            ->limit(25)
            ->limit(1, 'files/type')
            ->execute();

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
    public function viewClientManagementClientCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
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
    public function viewClientManagementClientProfile(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $head = $response->get('Content')->getData('head');
        $head->addAsset(AssetType::CSS, 'Resources/chartjs/Chartjs/chart.css');
        $head->addAsset(AssetType::JSLATE, 'Resources/chartjs/Chartjs/chart.js');
        $head->addAsset(AssetType::JSLATE, 'Modules/ClientManagement/Controller.js', ['type' => 'module']);

        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/ClientManagement/Theme/Backend/client-profile');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1003102001, $request, $response));

        /** @var \Modules\ClientManagement\Models\Client $client */
        $client = ClientMapper::get()
            ->with('profile')
            ->with('profile/account')
            ->with('contactElements')
            ->with('mainAddress')
            ->with('files')->limit(5, 'files')->sort('files/id', OrderType::DESC)
            ->with('notes')->limit(5, 'notes')->sort('notes/id', OrderType::DESC)
            ->where('id', (int) $request->getData('id'))
            ->execute();

        $view->setData('client', $client);

        // stats
        if ($this->app->moduleManager->isActive('Billing')) {
            $ytd            = SalesBillMapper::getSalesByClientId($client->getId(), new SmartDateTime('Y-01-01'), new SmartDateTime('now'));
            $mtd            = SalesBillMapper::getSalesByClientId($client->getId(), new SmartDateTime('Y-m-01'), new SmartDateTime('now'));
            $lastOrder      = SalesBillMapper::getLastOrderDateByClientId($client->getId());
            $newestInvoices = SalesBillMapper::getAll()
                ->with('type')
                ->with('type/l11n')
                ->with('client')
                ->where('client', $client->getId())
                ->where('type/l11n/language', $response->getLanguage())
                ->sort('id', OrderType::DESC)
                ->limit(5)
                ->execute();

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
    public function viewClientManagementClientAnalysis(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
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
    public function viewClientAnalysis(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $head = $response->get('Content')->getData('head');
        $head->addAsset(AssetType::CSS, 'Resources/chartjs/Chartjs/chart.css');
        $head->addAsset(AssetType::JSLATE, 'Resources/chartjs/Chartjs/chart.js');
        $head->addAsset(AssetType::JSLATE, 'Modules/ClientManagement/Controller.js', ['type' => 'module']);

        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/ClientManagement/Theme/Backend/client-analysis');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1001602001, $request, $response));

        $monthlySalesCosts = [];
        for ($i = 1; $i < 13; ++$i) {
            $monthlySalesCosts[] = [
                'net_sales' => $sales = \mt_rand(1200000000, 2000000000),
                'net_costs' => (int) ($sales * \mt_rand(25, 55) / 100),
                'year'      => 2020,
                'month'     => $i,
            ];
        }

        $view->addData('monthlySalesCosts', $monthlySalesCosts);

        /////
        $monthlySalesCustomer = [];
        for ($i = 1; $i < 13; ++$i) {
            $monthlySalesCustomer[] = [
                'net_sales' => $sales = \mt_rand(1200000000, 2000000000),
                'customers' => \mt_rand(200, 400),
                'year'      => 2020,
                'month'     => $i,
            ];
        }

        $view->addData('monthlySalesCustomer', $monthlySalesCustomer);

        $annualSalesCustomer = [];
        for ($i = 1; $i < 11; ++$i) {
            $annualSalesCustomer[] = [
                'net_sales' => $sales = \mt_rand(1200000000, 2000000000) * 12,
                'customers' => \mt_rand(200, 400) * 6,
                'year'      => 2020 - 10 + $i,
            ];
        }

        $view->addData('annualSalesCustomer', $annualSalesCustomer);

        /////
        $monthlyCustomerRetention = [];
        for ($i = 1; $i < 10; ++$i) {
            $monthlyCustomerRetention[] = [
                'customers' => \mt_rand(200, 400),
                'year'      => \date('y') - 9 + $i,
            ];
        }

        $view->addData('monthlyCustomerRetention', $monthlyCustomerRetention);

        /////
        $currentCustomerRegion = [
            'Europe'  => (int) (\mt_rand(200, 400) / 4),
            'America' => (int) (\mt_rand(200, 400) / 4),
            'Asia'    => (int) (\mt_rand(200, 400) / 4),
            'Africa'  => (int) (\mt_rand(200, 400) / 4),
            'CIS'     => (int) (\mt_rand(200, 400) / 4),
            'Other'   => (int) (\mt_rand(200, 400) / 4),
        ];

        $view->addData('currentCustomerRegion', $currentCustomerRegion);

        $annualCustomerRegion = [];
        for ($i = 1; $i < 11; ++$i) {
            $annualCustomerRegion[] = [
                'year'    => 2020 - 10 + $i,
                'Europe'  => $a = (int) (\mt_rand(200, 400) / 4),
                'America' => $b = (int) (\mt_rand(200, 400) / 4),
                'Asia'    => $c = (int) (\mt_rand(200, 400) / 4),
                'Africa'  => $d = (int) (\mt_rand(200, 400) / 4),
                'CIS'     => $e = (int) (\mt_rand(200, 400) / 4),
                'Other'   => $f = (int) (\mt_rand(200, 400) / 4),
                'Total'   => $a + $b + $c + $d + $e + $f,
            ];
        }

        $view->addData('annualCustomerRegion', $annualCustomerRegion);

        /////
        $currentCustomersRep = [];
        for ($i = 1; $i < 13; ++$i) {
            $currentCustomersRep['Rep ' . $i] = [
                'customers' => (int) (\mt_rand(200, 400) / 12),
            ];
        }

        \uasort($currentCustomersRep, function($a, $b) {
            return $b['customers'] <=> $a['customers'];
        });

        $view->addData('currentCustomersRep', $currentCustomersRep);

        $annualCustomersRep = [];
        for ($i = 1; $i < 13; ++$i) {
            $annualCustomersRep['Rep ' . $i] = [];

            for ($j = 1; $j < 11; ++$j) {
                $annualCustomersRep['Rep ' . $i][] = [
                    'customers' => (int) (\mt_rand(200, 400) / 12),
                    'year'      => 2020 - 10 + $j,
                ];
            }
        }

        $view->addData('annualCustomersRep', $annualCustomersRep);

        /////
        $currentCustomersCountry = [];
        for ($i = 1; $i < 51; ++$i) {
            $country                                           = ISO3166NameEnum::getRandom();
            $currentCustomersCountry[\substr($country, 0, 20)] = [
                'customers' => (int) (\mt_rand(200, 400) / 12),
            ];
        }

        \uasort($currentCustomersCountry, function($a, $b) {
            return $b['customers'] <=> $a['customers'];
        });

        $view->addData('currentCustomersCountry', $currentCustomersCountry);

        $annualCustomersCountry = [];
        for ($i = 1; $i < 51; ++$i) {
            $countryCode                                          = ISO3166CharEnum::getRandom();
            $countryName                                          = ISO3166NameEnum::getByName('_' . $countryCode);
            $annualCustomersCountry[\substr($countryName, 0, 20)] = [];

            for ($j = 1; $j < 11; ++$j) {
                $annualCustomersCountry[\substr($countryName, 0, 20)][] = [
                    'customers' => (int) (\mt_rand(200, 400) / 12),
                    'year'      => 2020 - 10 + $j,
                    'name'      => $countryName,
                    'code'      => $countryCode,
                ];
            }
        }

        $view->addData('annualCustomersCountry', $annualCustomersCountry);

        /////
        $customerGroups = [];
        for ($i = 1; $i < 7; ++$i) {
            $customerGroups['Group ' . $i] = [
                'customers' => (int) (\mt_rand(200, 400) / 12),
            ];
        }

        $view->addData('customerGroups', $customerGroups);

        return $view;
    }
}

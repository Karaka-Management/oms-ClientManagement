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

namespace Modules\ClientManagement\Controller;

use Modules\Billing\Models\SalesBillMapper;
use Modules\ClientManagement\Models\Attribute\ClientAttributeTypeL11nMapper;
use Modules\ClientManagement\Models\Attribute\ClientAttributeTypeMapper;
use Modules\ClientManagement\Models\Attribute\ClientAttributeValueMapper;
use Modules\ClientManagement\Models\ClientMapper;
use Modules\Media\Models\MediaMapper;
use Modules\Media\Models\MediaTypeMapper;
use phpOMS\Asset\AssetType;
use phpOMS\Contract\RenderableInterface;
use phpOMS\DataStorage\Database\Query\Builder;
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
 * @license OMS License 2.0
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
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewClientManagementAttributeTypeList(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/ClientManagement/Theme/Backend/attribute-type-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1004801001, $request, $response);

        /** @var \Modules\Attribute\Models\AttributeType[] $attributes */
        $attributes = ClientAttributeTypeMapper::getAll()
            ->with('l11n')
            ->where('l11n/language', $response->header->l11n->language)
            ->execute();

        $view->data['attributes'] = $attributes;

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewClientManagementAttributeValues(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/ClientManagement/Theme/Backend/attribute-value-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1004801001, $request, $response);

        /** @var \Modules\Attribute\Models\AttributeValue[] $attributes */
        $attributes = ClientAttributeValueMapper::getAll()
            ->with('l11n')
            ->where('l11n/language', $response->header->l11n->language)
            ->execute();

        $view->data['attributes'] = $attributes;

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewClientManagementAttributeType(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/ClientManagement/Theme/Backend/attribute-type');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1004801001, $request, $response);

        /** @var \Modules\Attribute\Models\AttributeType $attribute */
        $attribute = ClientAttributeTypeMapper::get()
            ->with('l11n')
            ->where('id', (int) $request->getData('id'))
            ->where('l11n/language', $response->header->l11n->language)
            ->execute();

        $l11ns = ClientAttributeTypeL11nMapper::getAll()
            ->where('ref', $attribute->id)
            ->execute();

        $view->data['attribute'] = $attribute;
        $view->data['l11ns']     = $l11ns;

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewClientManagementClientList(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/ClientManagement/Theme/Backend/client-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003102001, $request, $response);

        /** @var \Modules\ClientManagement\Models\Client $client */
        $client = ClientMapper::getAll()
            ->with('account')
            ->with('mainAddress')
            ->limit(25)
            ->execute();

        $view->data['client'] = $client;

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewClientManagementClientCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/ClientManagement/Theme/Backend/client-create');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003102001, $request, $response);

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewClientManagementClientProfile(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $head  = $response->data['Content']->head;
        $nonce = $this->app->appSettings->getOption('script-nonce');

        $head->addAsset(AssetType::CSS, 'Resources/chartjs/Chartjs/chart.css');
        $head->addAsset(AssetType::JSLATE, 'Resources/chartjs/Chartjs/chart.js', ['nonce' => $nonce]);
        $head->addAsset(AssetType::JSLATE, 'Resources/OpenLayers/OpenLayers.js', ['nonce' => $nonce]);
        $head->addAsset(AssetType::JSLATE, 'Modules/ClientManagement/Controller.js', ['nonce' => $nonce, 'type' => 'module']);

        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/ClientManagement/Theme/Backend/client-profile');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003102001, $request, $response);

        /** @var \Modules\ClientManagement\Models\Client $client */
        $client = ClientMapper::get()
            ->with('account')
            ->with('contactElements')
            ->with('mainAddress')
            ->with('files')->limit(5, 'files')->sort('files/id', OrderType::DESC)
            ->with('notes')->limit(5, 'notes')->sort('notes/id', OrderType::DESC)
            ->where('id', (int) $request->getData('id'))
            ->execute();

        $view->data['client'] = $client;

        // Get item profile image
        // It might not be part of the 5 newest item files from above
        // @todo: It would be nice to have something like this as a default method in the model e.g.
        // ItemManagement::getRelations()->with('types')->where(...);
        // This should return the relations and NOT the model itself
        $query   = new Builder($this->app->dbPool->get());
        $results = $query->selectAs(ClientMapper::HAS_MANY['files']['external'], 'file')
            ->from(ClientMapper::TABLE)
            ->leftJoin(ClientMapper::HAS_MANY['files']['table'])
                ->on(ClientMapper::HAS_MANY['files']['table'] . '.' . ClientMapper::HAS_MANY['files']['self'], '=', ClientMapper::TABLE . '.' . ClientMapper::PRIMARYFIELD)
            ->leftJoin(MediaMapper::TABLE)
                ->on(ClientMapper::HAS_MANY['files']['table'] . '.' . ClientMapper::HAS_MANY['files']['external'], '=', MediaMapper::TABLE . '.' . MediaMapper::PRIMARYFIELD)
             ->leftJoin(MediaMapper::HAS_MANY['types']['table'])
                ->on(MediaMapper::TABLE . '.' . MediaMapper::PRIMARYFIELD, '=', MediaMapper::HAS_MANY['types']['table'] . '.' . MediaMapper::HAS_MANY['types']['self'])
            ->leftJoin(MediaTypeMapper::TABLE)
                ->on(MediaMapper::HAS_MANY['types']['table'] . '.' . MediaMapper::HAS_MANY['types']['external'], '=', MediaTypeMapper::TABLE . '.' . MediaTypeMapper::PRIMARYFIELD)
            ->where(ClientMapper::HAS_MANY['files']['self'], '=', $client->id)
            ->where(MediaTypeMapper::TABLE . '.' . MediaTypeMapper::getColumnByMember('name'), '=', 'client_profile_image');

        $clientImage = MediaMapper::get()
            ->with('types')
            ->where('id', $results)
            ->limit(1)
            ->execute();

        $view->data['clientImage'] = $clientImage;

        // stats
        if ($this->app->moduleManager->isActive('Billing')) {
            $ytd            = SalesBillMapper::getSalesByClientId($client->id, new SmartDateTime('Y-01-01'), new SmartDateTime('now'));
            $mtd            = SalesBillMapper::getSalesByClientId($client->id, new SmartDateTime('Y-m-01'), new SmartDateTime('now'));
            $lastOrder      = SalesBillMapper::getLastOrderDateByClientId($client->id);
            $newestInvoices = SalesBillMapper::getAll()
                ->with('type')
                ->with('type/l11n')
                ->with('client')
                ->where('client', $client->id)
                ->where('type/l11n/language', $response->header->l11n->language)
                ->sort('id', OrderType::DESC)
                ->limit(5)
                ->execute();

            $monthlySalesCosts = SalesBillMapper::getClientMonthlySalesCosts($client->id, (new SmartDateTime('now'))->createModify(-1), new SmartDateTime('now'));
            $items             = SalesBillMapper::getClientItem($client->id, (new SmartDateTime('now'))->createModify(-1), new SmartDateTime('now'));
        } else {
            $ytd               = new Money();
            $mtd               = new Money();
            $lastOrder         = null;
            $newestInvoices    = [];
            $monthlySalesCosts = [];
            $items             = [];
        }

        $view->data['ytd']               = $ytd;
        $view->data['mtd']               = $mtd;
        $view->data['lastOrder']         = $lastOrder;
        $view->data['newestInvoices']    = $newestInvoices;
        $view->data['monthlySalesCosts'] = $monthlySalesCosts;
        $view->data['items']             = $items;

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewClientManagementClientAnalysis(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        return new View($this->app->l11nManager, $request, $response);
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewClientAnalysis(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $head  = $response->data['Content']->head;
        $nonce = $this->app->appSettings->getOption('script-nonce');

        $head->addAsset(AssetType::CSS, 'Resources/chartjs/Chartjs/chart.css');
        $head->addAsset(AssetType::JSLATE, 'Resources/chartjs/Chartjs/chart.js', ['nonce' => $nonce]);
        $head->addAsset(AssetType::JSLATE, 'Modules/ClientManagement/Controller.js', ['nonce' => $nonce, 'type' => 'module']);

        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/ClientManagement/Theme/Backend/client-analysis');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1001602001, $request, $response);

        $monthlySalesCosts = [];
        for ($i = 1; $i < 13; ++$i) {
            $monthlySalesCosts[] = [
                'net_sales' => $sales = \mt_rand(1200000000, 2000000000),
                'net_costs' => (int) ($sales * \mt_rand(25, 55) / 100),
                'year'      => 2020,
                'month'     => $i,
            ];
        }

        $view->data['monthlySalesCosts'] = $monthlySalesCosts;

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

        $view->data['monthlySalesCustomer'] = $monthlySalesCustomer;

        $annualSalesCustomer = [];
        for ($i = 1; $i < 11; ++$i) {
            $annualSalesCustomer[] = [
                'net_sales' => $sales = \mt_rand(1200000000, 2000000000) * 12,
                'customers' => \mt_rand(200, 400) * 6,
                'year'      => 2020 - 10 + $i,
            ];
        }

        $view->data['annualSalesCustomer'] = $annualSalesCustomer;

        /////
        $monthlyCustomerRetention = [];
        for ($i = 1; $i < 10; ++$i) {
            $monthlyCustomerRetention[] = [
                'customers' => \mt_rand(200, 400),
                'year'      => \date('y') - 9 + $i,
            ];
        }

        $view->data['monthlyCustomerRetention'] = $monthlyCustomerRetention;

        /////
        $currentCustomerRegion = [
            'Europe'  => (int) (\mt_rand(200, 400) / 4),
            'America' => (int) (\mt_rand(200, 400) / 4),
            'Asia'    => (int) (\mt_rand(200, 400) / 4),
            'Africa'  => (int) (\mt_rand(200, 400) / 4),
            'CIS'     => (int) (\mt_rand(200, 400) / 4),
            'Other'   => (int) (\mt_rand(200, 400) / 4),
        ];

        $view->data['currentCustomerRegion'] = $currentCustomerRegion;

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

        $view->data['annualCustomerRegion'] = $annualCustomerRegion;

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

        $view->data['currentCustomersRep'] = $currentCustomersRep;

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

        $view->data['annualCustomersRep'] = $annualCustomersRep;

        /////
        $currentCustomersCountry = [];
        for ($i = 1; $i < 51; ++$i) {
            $country                                           = (string) ISO3166NameEnum::getRandom();
            $currentCustomersCountry[\substr($country, 0, 20)] = [
                'customers' => (int) (\mt_rand(200, 400) / 12),
            ];
        }

        \uasort($currentCustomersCountry, function($a, $b) {
            return $b['customers'] <=> $a['customers'];
        });

        $view->data['currentCustomersCountry'] = $currentCustomersCountry;

        $annualCustomersCountry = [];
        for ($i = 1; $i < 51; ++$i) {
            $countryCode                                          = ISO3166CharEnum::getRandom();
            $countryName                                          = (string) ISO3166NameEnum::getByName('_' . $countryCode);
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

        $view->data['annualCustomersCountry'] = $annualCustomersCountry;

        /////
        $customerGroups = [];
        for ($i = 1; $i < 7; ++$i) {
            $customerGroups['Group ' . $i] = [
                'customers' => (int) (\mt_rand(200, 400) / 12),
            ];
        }

        $view->data['customerGroups'] = $customerGroups;

        return $view;
    }
}

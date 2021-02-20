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

use Modules\Billing\Models\SalesBillMapper;
use Modules\Billing\Models\BillTypeL11n;
use Modules\ClientManagement\Models\ClientMapper;
use phpOMS\Asset\AssetType;
use phpOMS\Contract\RenderableInterface;
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

        $client = ClientMapper::getAfterPivot(0, null, 25);
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

        $client = ClientMapper::get((int) $request->getData('id'));
        $view->setData('client', $client);

        // stats
        if ($this->app->moduleManager->isActive('Billing')) {
            $ytd               = SalesBillMapper::getSalesByClientId($client->getId(), new SmartDateTime('Y-01-01'), new SmartDateTime('now'));
            $mtd               = SalesBillMapper::getSalesByClientId($client->getId(), new SmartDateTime('Y-m-01'), new SmartDateTime('now'));
            $lastOrder         = SalesBillMapper::getLastOrderDateByClientId($client->getId());
            $newestInvoices    = SalesBillMapper::withConditional('language', $response->getLanguage(), [BillTypeL11n::class])::getNewestClientInvoices($client->getId(), 5);
            $monthlySalesCosts = SalesBillMapper::getClientMonthlySalesCosts($client->getId(), (new SmartDateTime('now'))->createModify(-1), new SmartDateTime('now'));
        } else {
            $ytd               = new Money();
            $mtd               = new Money();
            $lastOrder         = null;
            $newestInvoices    = [];
            $monthlySalesCosts = [];
        }

        $view->addData('ytd', $ytd);
        $view->addData('mtd', $mtd);
        $view->addData('lastOrder', $lastOrder);
        $view->addData('newestInvoices', $newestInvoices);
        $view->addData('monthlySalesCosts', $monthlySalesCosts);

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
}

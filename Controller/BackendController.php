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

use Modules\Auditor\Models\AuditMapper;
use Modules\ClientManagement\Models\Attribute\ClientAttributeTypeL11nMapper;
use Modules\ClientManagement\Models\Attribute\ClientAttributeTypeMapper;
use Modules\ClientManagement\Models\Attribute\ClientAttributeValueL11nMapper;
use Modules\ClientManagement\Models\Attribute\ClientAttributeValueMapper;
use Modules\ClientManagement\Models\ClientMapper;
use Modules\ItemManagement\Models\Attribute\ItemAttributeTypeMapper;
use Modules\ItemManagement\Models\Attribute\ItemAttributeValueL11nMapper;
use Modules\Media\Models\MediaMapper;
use Modules\Media\Models\MediaTypeMapper;
use Modules\Organization\Models\Attribute\UnitAttributeMapper;
use phpOMS\Asset\AssetType;
use phpOMS\Contract\RenderableInterface;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Query\OrderType;
use phpOMS\DataStorage\Database\Query\Where;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Utils\StringUtils;
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
     * Routing end-point for application behavior.
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
     * Routing end-point for application behavior.
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
     * Routing end-point for application behavior.
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
     * Routing end-point for application behavior.
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
     * Routing end-point for application behavior.
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
     * Routing end-point for application behavior.
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
    public function viewClientManagementClientView(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $head  = $response->data['Content']->head;
        $nonce = $this->app->appSettings->getOption('script-nonce');

        $head->addAsset(AssetType::CSS, 'Resources/chartjs/chart.css');
        $head->addAsset(AssetType::JSLATE, 'Resources/chartjs/chart.js', ['nonce' => $nonce]);
        $head->addAsset(AssetType::JSLATE, 'Resources/OpenLayers/OpenLayers.js', ['nonce' => $nonce]);
        $head->addAsset(AssetType::JSLATE, 'Modules/ClientManagement/Controller.js', ['nonce' => $nonce, 'type' => 'module']);

        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/ClientManagement/Theme/Backend/client-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1003102001, $request, $response);

        $pkType  = 'id';
        $pkValue = $request->getDataInt('id');
        if ($pkValue === null) {
            $pkType  = 'number';
            $pkValue = $request->getDataString('number');
        }

        /** @var \Modules\ClientManagement\Models\Client */
        $view->data['client'] = ClientMapper::get()
            ->with('account')
            ->with('account/addresses')
            ->with('account/contacts')
            ->with('mainAddress')
            ->with('files')->limit(5, 'files')->sort('files/id', OrderType::DESC)
            ->with('notes')->limit(5, 'notes')->sort('notes/id', OrderType::DESC)
            ->with('attributes')
            ->with('attributes/type')
            ->with('attributes/type/l11n')
            ->with('attributes/value')
            //->with('attributes/value/l11n')
            //->with('attributes/value/l11n')
            ->where($pkType, $pkValue)
            ->where('attributes/type/l11n/language', $response->header->l11n->language)
            //->where('attributes/value/l11n/language', $response->header->l11n->language)
            /*
            ->where('attributes/value/l11n', (new Where($this->app->dbPool->get()))
                ->where(ClientAttributeValueL11nMapper::getColumnByMember('ref'), '=', null)
                ->orWhere(ClientAttributeValueL11nMapper::getColumnByMember('language'), '=', $response->header->l11n->language))
            */
            ->execute();

        $view->data['attributeView']                               = new \Modules\Attribute\Theme\Backend\Components\AttributeView($this->app->l11nManager, $request, $response);
        $view->data['attributeView']->data['default_localization'] = $this->app->l11nServer;

        /** @var \Modules\Attribute\Models\AttributeType[] */
        $view->data['attributeTypes'] = ClientAttributeTypeMapper::getAll()
            ->with('l11n')
            ->where('l11n/language', $response->header->l11n->language)
            ->execute();

        // Get item profile image
        // It might not be part of the 5 newest item files from above
        // @todo It would be nice to have something like this as a default method in the model e.g.
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
            ->where(ClientMapper::HAS_MANY['files']['self'], '=', $view->data['client']->id)
            ->where(MediaTypeMapper::TABLE . '.' . MediaTypeMapper::getColumnByMember('name'), '=', 'client_profile_image');

        $view->data['clientImage'] = MediaMapper::get()
            ->with('types')
            ->where('id', $results)
            ->limit(1)
            ->execute();

        $businessStart = UnitAttributeMapper::get()
            ->with('type')
            ->with('value')
            ->where('ref', $this->app->unitId)
            ->where('type/name', 'business_year_start')
            ->execute();

        $view->data['business_start'] = $businessStart->id === 0 ? 1 : $businessStart->value->getValue();

        $view->data['hasBilling'] = $this->app->moduleManager->isActive('Billing');

        /** @var \Modules\Billing\Models\Price\Price[] */
        $view->data['prices'] = $view->data['hasBilling']
            ? \Modules\Billing\Models\Price\PriceMapper::getAll()
                ->where('client', $view->data['client']->id)
                ->where('type', \Modules\Billing\Models\Price\PriceType::SALES)
                ->execute()
            : [];

        $tmp = ItemAttributeTypeMapper::getAll()
            ->with('defaults')
            ->with('defaults/l11n')
            ->where('name', [
                'segment', 'section', 'sales_group', 'product_group', 'product_type',
                'sales_tax_code', 'purchase_tax_code',
            ], 'IN')
            ->where('defaults/l11n', (new Where($this->app->dbPool->get()))
                ->where(ItemAttributeValueL11nMapper::getColumnByMember('ref'), '=', null)
                ->orWhere(ItemAttributeValueL11nMapper::getColumnByMember('language'), '=', $response->header->l11n->language))
            ->execute();

        $defaultAttributeTypes = [];
        foreach ($tmp as $t) {
            $defaultAttributeTypes[$t->name] = $t;
        }

        $view->data['defaultAttributeTypes'] = $defaultAttributeTypes;

        $tmp = ClientAttributeTypeMapper::getAll()
            ->with('defaults')
            ->with('defaults/l11n')
            ->where('name', [
                'segment', 'section', 'client_group', 'client_type',
                'sales_tax_code',
            ], 'IN')
            ->where('defaults/l11n', (new Where($this->app->dbPool->get()))
                ->where(ClientAttributeValueL11nMapper::getColumnByMember('ref'), '=', null)
                ->orWhere(ClientAttributeValueL11nMapper::getColumnByMember('language'), '=', $response->header->l11n->language))
            ->execute();

        $clientSegmentationTypes = [];
        foreach ($tmp as $t) {
            $clientSegmentationTypes[$t->name] = $t;
        }

        $view->data['clientSegmentationTypes'] = $clientSegmentationTypes;

        /** @var \Modules\Auditor\Models\Audit[] */
        $view->data['audits'] = AuditMapper::getAll()
            ->where('type', StringUtils::intHash(ClientMapper::class))
            ->where('module', 'ClientManagement')
            ->where('ref', (string) $view->data['client']->id)
            ->execute();

        // @todo join audit with files, attributes, localization, prices, notes, ...

        /** @var \Modules\Media\Models\Media[] */
        $view->data['files'] = MediaMapper::getAll()
            ->with('types')
            ->join('id', ClientMapper::class, 'files') // id = media id, files = client relations
                ->on('id', $view->data['client']->id, relation: 'files') // id = item id
            ->execute();

        $view->data['media-upload'] = new \Modules\Media\Theme\Backend\Components\Upload\BaseView($this->app->l11nManager, $request, $response);
        $view->data['note']         = new \Modules\Editor\Theme\Backend\Components\Note\BaseView($this->app->l11nManager, $request, $response);

        $view->data['address-component'] = new \Modules\Admin\Theme\Backend\Components\AddressEditor\AddressView($this->app->l11nManager, $request, $response);
        $view->data['contact-component'] = new \Modules\Admin\Theme\Backend\Components\ContactEditor\ContactView($this->app->l11nManager, $request, $response);

        return $view;
    }

    /**
     * Routing end-point for application behavior.
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
}

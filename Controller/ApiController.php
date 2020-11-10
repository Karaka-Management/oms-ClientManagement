<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   Modules\ClientManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\ClientManagement\Controller;

use Modules\ClientManagement\Models\ClientMapper;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Model\Message\FormValidation;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use Modules\ClientManagement\Models\Client;
use Modules\Admin\Models\Account;
use Modules\Profile\Models\Profile;
use Modules\Admin\Models\Address;

/**
 * ClientManagement class.
 *
 * @package Modules\ClientManagement
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class ApiController extends Controller
{
    /**
     * Api method to create news article
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiClientCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        if (!empty($val = $this->validateClientCreate($request))) {
            $response->set('client_create', new FormValidation($val));
            $response->getHeader()->setStatusCode(RequestStatusCode::R_400);

            return;
        }

        $client = $this->createClientFromRequest($request);
        $this->createModel($request->getHeader()->getAccount(), $client, ClientMapper::class, 'client', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Client', 'Client successfully created', $client);
    }

    /**
     * Method to create news article from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return Client
     *
     * @since 1.0.0
     */
    private function createClientFromRequest(RequestAbstract $request) : Client
    {
        $account = new Account();
        $account->setName1($request->getData('name1') ?? '');
        $account->setName2($request->getData('name2') ?? '');

        $profile = new Profile($account);

        $client = new Client();
        $client->setNumber($request->getData('number') ?? '');
        $client->setProfile($profile);

        $addr = new Address();
        $addr->setAddress($request->getData('address') ?? '');
        $addr->setPostal($request->getData('postal') ?? '');
        $addr->setCity($request->getData('city') ?? '');
        $addr->setCountry($request->getData('country') ?? '');
        $addr->setState($request->getData('state') ?? '');
        $client->setMainAddress($addr);

        return $client;
    }

    /**
     * Validate news create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateClientCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['number'] = empty($request->getData('number')))
            || ($val['name1'] = empty($request->getData('name1')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiContactElementCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        $profileModule  = $this->app->moduleManager->get('Profile');

        if (!empty($val = $profileModule->validateContactElementCreate($request))) {
            $response->set('contact_element_create', new FormValidation($val));
            $response->getHeader()->setStatusCode(RequestStatusCode::R_400);

            return;
        }

        $contactElement = $profileModule->createContactElementFromRequest($request);

        $this->createModel($request->getHeader()->getAccount(), $contactElement, ContactElementMapper::class, 'client-contactElement', $request->getOrigin());
        $this->createModelRelation(
            $request->getHeader()->getAccount(),
            (int) $request->getData('client'),
            $contactElement->getId(),
            ClientMapper::class, 'contactElements', '', $request->getOrigin()
        );
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Contact Element', 'Contact element successfully created', $contactElement);
    }
}

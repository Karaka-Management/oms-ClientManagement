<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\ClientManagement\tests\Models;

use Modules\Admin\Models\NullAccount;
use Modules\ClientManagement\Models\Client;
use Modules\ClientManagement\Models\ClientMapper;
use Modules\Profile\Models\NullProfile;
use Modules\Profile\Models\Profile;
use Modules\Profile\Models\ProfileMapper;

/**
 * @internal
 */
final class ClientMapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\ClientManagement\Models\ClientMapper
     * @group module
     */
    public function testCR() : void
    {
        $client         = new Client();
        $client->number = '123456789';

        // This is required because by default a NullAccount without an ID is created in the Profile model
        // but NullModels without ids are handled like "null" values which are not allowed for Accounts.
        $profile = ProfileMapper::get()->where('account', 1)->execute();
        $profile = $profile instanceof NullProfile ? new Profile() : $profile;
        if ($profile->account->getId() === 0) {
            $profile->account = new NullAccount(1);
        }

        $client->profile = $profile;

        $id = ClientMapper::create()->execute($client);
        self::assertGreaterThan(0, $client->getId());
        self::assertEquals($id, $client->getId());
    }
}

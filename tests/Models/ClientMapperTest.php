<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
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
class ClientMapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\ClientManagement\Models\ClientMapper
     * @group module
     */
    public function testCR() : void
    {
        $client = new Client();
        $client->setNumber('123456789');

        // This is required because by default a NullAccount without an ID is created in the Profile model
        // but NullModels without ids are handled like "null" values which are not allowed for Accounts.
        $profile = ProfileMapper::getFor(1, 'account');
        $profile = $profile instanceof NullProfile ? new Profile() : $profile;
        if ($profile->getAccount()->getId() === 0) {
            $profile->setAccount(new NullAccount(1));
        }

        $client->setProfile($profile);

        $id = ClientMapper::create($client);
        self::assertGreaterThan(0, $client->getId());
        self::assertEquals($id, $client->getId());
    }

    /**
     * @group volume
     * @group module
     * @coversNothing
     */
    public function testClientVolume() : void
    {
        $profile = ProfileMapper::getFor(1, 'account');
        $profile = $profile instanceof NullProfile ? new Profile() : $profile;
        if ($profile->getAccount()->getId() === 0) {
            $profile->setAccount(new NullAccount(1));
        }

        for ($i = 0; $i < 100; ++$i) {
            $client = new Client();
            $client->setNumber((string) \mt_rand(100000, 999999));

            $client->setProfile($profile);
            ClientMapper::create($client);
        }
    }
}

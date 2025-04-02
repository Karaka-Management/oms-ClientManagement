<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\ClientManagement\tests\Models;

use Modules\Admin\Models\NullAccount;
use Modules\ClientManagement\Models\Client;
use Modules\ClientManagement\Models\ClientMapper;
use Modules\Profile\Models\Profile;
use Modules\Profile\Models\ProfileMapper;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\ClientManagement\Models\ClientMapper::class)]
final class ClientMapperTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testCR() : void
    {
        $client         = new Client();
        $client->number = '123456789';

        // This is required because by default a NullAccount without an ID is created in the Profile model
        // but NullModels without ids are handled like "null" values which are not allowed for Accounts.
        $profile = ProfileMapper::get()->where('account', 1)->execute();
        $profile = $profile->id === 0 ? new Profile() : $profile;
        if ($profile->account->id === 0) {
            $profile->account = new NullAccount(1);
        }

        $client->profile = $profile;

        $id = ClientMapper::create()->execute($client);
        self::assertGreaterThan(0, $client->id);
        self::assertEquals($id, $client->id);
    }
}

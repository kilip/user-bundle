<?php

/*
 * This file is part of the kilip/user-bundle project.
 *
 * (c) Anthonius Munthi <https://itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Kilip\UserBundle\Unit\Util;

use Kilip\UserBundle\Contract\CanonicalizerInterface;
use Kilip\UserBundle\Contract\UserInterface;
use Kilip\UserBundle\Util\CanonicalFieldsUpdater;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CanonicalFieldsUpdaterTest extends TestCase
{
    /**
     * @var MockObject|CanonicalizerInterface
     */
    private $usernameCanonicalizer;

    /**
     * @var MockObject|CanonicalizerInterface
     */
    private $emailCanonicalizer;

    /**
     * @var CanonicalFieldsUpdater
     */
    private $updater;

    protected function setUp(): void
    {
        $this->usernameCanonicalizer = $this->createMock(CanonicalizerInterface::class);
        $this->emailCanonicalizer = $this->createMock(CanonicalizerInterface::class);

        $this->updater = new CanonicalFieldsUpdater($this->usernameCanonicalizer, $this->emailCanonicalizer);
    }

    public function testUpdate()
    {
        $user = $this->createUserMock('Test', 'Test@Example.com');
        $updater = $this->updater;

        $this->usernameCanonicalizer->expects($this->once())
            ->method('canonicalize')
            ->with('Test')
            ->willReturnCallback('strtolower');
        $this->emailCanonicalizer->expects($this->once())
            ->method('canonicalize')
            ->with('Test@Example.com')
            ->willReturnCallback('strtolower');

        $user->expects($this->once())
            ->method('setUsernameCanonical')
            ->with('test');
        $user->expects($this->once())
            ->method('setEmailCanonical')
            ->with('test@example.com');
        $updater->update($user);
    }

    /**
     * @param string $username
     * @param string $email
     *
     * @return MockObject|UserInterface
     */
    private function createUserMock($username, $email)
    {
        $user = $this->createMock(UserInterface::class);

        $user->expects($this->once())
            ->method('getUsername')
            ->willReturn($username);
        $user->expects($this->once())
            ->method('getEmail')
            ->willReturn($email);

        return $user;
    }
}

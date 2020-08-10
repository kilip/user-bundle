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

namespace Tests\Kilip\UserBundle\Unit\Doctrine;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Kilip\UserBundle\Contract\CanonicalFieldsUpdaterInterface;
use Kilip\UserBundle\Contract\PasswordUpdaterInterface;
use Kilip\UserBundle\Contract\UserInterface;
use Kilip\UserBundle\Doctrine\UserManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Kilip\UserBundle\Sandbox\Model\TestUser;

class UserManagerTest extends TestCase
{
    /**
     * @var MockObject|PasswordUpdaterInterface
     */
    private $passwordUpdater;

    /**
     * @var MockObject|CanonicalFieldsUpdaterInterface
     */
    private $canonicalFieldsUpdater;

    /**
     * @var MockObject|ObjectManager
     */
    private $om;

    /**
     * @var MockObject|ObjectRepository
     */
    private $repository;

    /**
     * @var UserManager
     */
    private $manager;

    protected function setUp(): void
    {
        $this->passwordUpdater = $this->createMock(PasswordUpdaterInterface::class);
        $this->canonicalFieldsUpdater = $this->createMock(CanonicalFieldsUpdaterInterface::class);
        $this->om = $this->createMock(ObjectManager::class);
        $this->repository = $this->createMock(ObjectRepository::class);

        $this->manager = new UserManager(
            $this->om,
            $this->passwordUpdater,
            $this->canonicalFieldsUpdater,
            TestUser::class
        );
    }

    public function testModel()
    {
        $manager = $this->manager;

        $this->assertEquals(TestUser::class, $manager->getClass());
        $this->assertInstanceOf(TestUser::class, $manager->createUser());
    }

    public function testStore()
    {
        $user = $this->createMock(UserInterface::class);
        $manager = $this->manager;

        $this->passwordUpdater->expects($this->once())
            ->method('hashPassword')
            ->with($user);
        $this->canonicalFieldsUpdater->expects($this->once())
            ->method('update')
            ->with($user);

        $this->om->expects($this->once())
            ->method('persist')
            ->with($user);

        $this->om->expects($this->once())
            ->method('flush');
        $manager->store($user);
    }

    public function testFindUser()
    {
        $user = $this->createMock(UserInterface::class);
        $manager = $this->manager;
        $this->om->expects($this->once())
            ->method('getRepository')
            ->with(TestUser::class)
            ->willReturn($this->repository);

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['username' => 'test'])
            ->willReturn($user);

        $this->assertSame($user, $manager->findUserBy(['username' => 'test']));
    }
}

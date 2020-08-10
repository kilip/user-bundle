<?php

namespace Tests\Kilip\UserBundle\Unit\Util;

use Kilip\UserBundle\Contract\UserInterface;
use Kilip\UserBundle\Util\PasswordUpdater;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\SelfSaltingEncoderInterface;

interface TestSelfSalting extends SelfSaltingEncoderInterface
{
    public function encodePassword($password, $salt);
}

class PasswordUpdaterTest extends TestCase
{
    /**
     * @var MockObject|EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var PasswordUpdater
     */
    private $updater;

    protected function setUp(): void
    {
        $this->encoderFactory = $this->createMock(EncoderFactoryInterface::class);
        $this->updater = new PasswordUpdater($this->encoderFactory);
    }

    public function testHashPassword()
    {
        $encoder = $this->createMock(PasswordEncoderInterface::class);
        $user = $this->getUser();
        $updater = $this->updater;

        $this->encoderFactory->expects($this->once())
            ->method('getEncoder')
            ->with($user)
            ->willReturn($encoder);

        $user->expects($this->once())
            ->method('setSalt')
            ->with($this->isType('string'));

        $encoder->expects($this->once())
            ->method('encodePassword')
            ->with('plain', 'some-salt')
            ->willReturn('encoded');

        $updater->hashPassword($user);
    }

    public function testWithSelfSaltingEncoder()
    {
        $encoder = $this->createMock(TestSelfSalting::class);
        $user = $this->getUser();
        $updater = $this->updater;

        $this->encoderFactory->expects($this->once())
            ->method('getEncoder')
            ->willReturn($encoder);
        $user->expects($this->once())
            ->method('setSalt')
            ->with(null);

        $encoder->expects($this->once())
            ->method('encodePassword')
            ->with('plain', 'some-salt')
            ->willReturn('encoded');

        $updater->hashPassword($user);
    }

    private function getUser()
    {
        $user = $this->createMock(UserInterface::class);
        $user->expects($this->once())
            ->method('getPlainPassword')
            ->willReturn('plain');

        $user->expects($this->once())
            ->method('getSalt')
            ->willReturn('some-salt');
        $user->expects($this->once())
            ->method('eraseCredentials');
        $user->expects($this->once())
            ->method('setPassword')
            ->with('encoded');

        return $user;
    }
}

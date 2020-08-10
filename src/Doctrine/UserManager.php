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

namespace Kilip\UserBundle\Doctrine;

use Doctrine\Persistence\ObjectManager;
use Kilip\UserBundle\Contract\CanonicalFieldsUpdaterInterface;
use Kilip\UserBundle\Contract\PasswordUpdaterInterface;
use Kilip\UserBundle\Contract\UserInterface;

class UserManager
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var PasswordUpdaterInterface
     */
    private $passwordUpdater;

    /**
     * @var CanonicalFieldsUpdaterInterface
     */
    private $canonicalFieldsUpdater;

    /**
     * @var string
     */
    private $userModel;

    public function __construct(
        ObjectManager $manager,
        PasswordUpdaterInterface $passwordUpdater,
        CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater,
        string $userModel
    ) {
        $this->manager = $manager;
        $this->passwordUpdater = $passwordUpdater;
        $this->canonicalFieldsUpdater = $canonicalFieldsUpdater;
        $this->userModel = $userModel;
    }

    /**
     * @param array $criteria
     *
     * @return object|UserInterface|null
     */
    public function findUserBy(array $criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->userModel;
    }

    /**
     * @return UserInterface
     */
    public function createUser()
    {
        $class = $this->getClass();

        return new $class();
    }

    public function store(UserInterface $user, $andFlush = true)
    {
        $this->updateCanonicalFields($user);
        $this->updatePassword($user);

        $this->manager->persist($user);
        if ($andFlush) {
            $this->manager->flush();
        }
    }

    /**
     * @return \Doctrine\Persistence\ObjectRepository
     */
    protected function getRepository()
    {
        return $this->manager->getRepository($this->userModel);
    }

    /**
     * @param UserInterface $user
     */
    public function updateCanonicalFields(UserInterface $user)
    {
        $this->canonicalFieldsUpdater->update($user);
    }

    /**
     * @param UserInterface $user
     */
    public function updatePassword(UserInterface $user)
    {
        $this->passwordUpdater->hashPassword($user);
    }
}

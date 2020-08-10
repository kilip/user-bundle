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

namespace Kilip\UserBundle\Contracts;

use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface
{
    /**
     * @param string $roles
     *
     * @return static
     */
    public function setRoles($roles);

    /**
     * @param string $password
     *
     * @return static
     */
    public function setPassword($password);

    /**
     * @param string $salt
     *
     * @return static
     */
    public function setSalt($salt);

    /**
     * @param string $username
     *
     * @return static
     */
    public function setUsername($username);

    /**
     * @param string $usernameCanonical
     *
     * @return static
     */
    public function setUsernameCanonical($usernameCanonical);

    /**
     * @return string
     */
    public function getUsernameCanonical();

    /**
     * @param string $email
     *
     * @return static
     */
    public function setEmail($email);

    /**
     * @return string|null
     */
    public function getEmail();

    /**
     * @param string $emailCanonical
     *
     * @return static
     */
    public function setEmailCanonical($emailCanonical);

    /**
     * @return string
     */
    public function getEmailCanonical();
}

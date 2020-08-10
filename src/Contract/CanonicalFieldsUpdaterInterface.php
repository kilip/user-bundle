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

namespace Kilip\UserBundle\Contract;

interface CanonicalFieldsUpdaterInterface
{
    /**
     * Update user canonical fields.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function update(UserInterface $user);

    /**
     * @param string $username
     *
     * @return string
     */
    public function canonicalizeUsername($username);

    /**
     * @param string $email
     *
     * @return string
     */
    public function canonicalizeEmail($email);
}

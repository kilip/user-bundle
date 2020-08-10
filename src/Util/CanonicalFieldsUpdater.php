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

namespace Kilip\UserBundle\Util;

use Kilip\UserBundle\Contract\CanonicalFieldsUpdaterInterface;
use Kilip\UserBundle\Contract\CanonicalizerInterface;
use Kilip\UserBundle\Contract\UserInterface;

class CanonicalFieldsUpdater implements CanonicalFieldsUpdaterInterface
{
    /**
     * @var CanonicalizerInterface
     */
    private $usernameCanonicalizer;

    /**
     * @var CanonicalizerInterface
     */
    private $emailCanonicalizer;

    public function __construct(
        CanonicalizerInterface $usernameCanonicalizer,
        CanonicalizerInterface $emailCanonicalizer
    ) {
        $this->usernameCanonicalizer = $usernameCanonicalizer;
        $this->emailCanonicalizer = $emailCanonicalizer;
    }

    public function update(UserInterface $user)
    {
        $user->setUsernameCanonical($this->canonicalizeUsername($user->getUsername()));
        $user->setEmailCanonical($this->canonicalizeEmail($user->getEmail()));
    }

    public function canonicalizeUsername($username)
    {
        return $this->usernameCanonicalizer->canonicalize($username);
    }

    public function canonicalizeEmail($email)
    {
        return $this->emailCanonicalizer->canonicalize($email);
    }
}

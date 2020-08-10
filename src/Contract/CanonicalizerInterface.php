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

interface CanonicalizerInterface
{
    /**
     * @param string|null $string
     *
     * @return string|null
     */
    public function canonicalize($string);
}

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

namespace Tests\Kilip\UserBundle\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as BaseKernelTestCase;
use Tests\Kilip\UserBundle\Sandbox\AppKernel;

abstract class KernelTestCase extends BaseKernelTestCase
{
    protected static function getKernelClass()
    {
        return AppKernel::class;
    }
}

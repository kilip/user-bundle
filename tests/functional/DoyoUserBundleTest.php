<?php

/*
 * This file is part of the DoyoLabs User Bundle project.
 *
 * (c) Anthonius Munthi <https://itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\DoyoLabs\UserBundle\Functional;

use DoyoLabs\UserBundle\DoyoUserBundle;

class DoyoUserBundleTest extends KernelTestCase
{
    public function testBundleLoaded()
    {
        $kernel = static::bootKernel();
        $kernel->boot();

        $this->assertInstanceOf(DoyoUserBundle::class, $kernel->getBundle('DoyoUserBundle'));
    }
}

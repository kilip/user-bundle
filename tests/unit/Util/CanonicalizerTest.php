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

use Kilip\UserBundle\Util\Canonicalizer;
use PHPUnit\Framework\TestCase;

class CanonicalizerTest extends TestCase
{
    /**
     * @param string $value
     * @param string $expected
     * @dataProvider canonicalizeProvider
     */
    public function testCanonicalize($value, $expected)
    {
        $canonicalizer = new Canonicalizer();

        $this->assertSame($expected, $canonicalizer->canonicalize($value));
    }

    public function canonicalizeProvider()
    {
        return [
            [null, null],
            ['FoO', 'foo'],
            [\chr(171), \PHP_VERSION_ID < 50600 ? \chr(171) : '?'],
        ];
    }
}

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

use Kilip\UserBundle\Contract\CanonicalizerInterface;

class Canonicalizer implements CanonicalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function canonicalize($string)
    {
        if (null === $string) {
            return null;
        }

        $encoding = mb_detect_encoding($string, mb_detect_order(), true);

        return $encoding
            ? mb_convert_case($string, MB_CASE_LOWER, $encoding)
            : mb_convert_case($string, MB_CASE_LOWER);
    }
}

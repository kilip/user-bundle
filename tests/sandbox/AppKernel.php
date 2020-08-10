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

namespace Tests\Kilip\UserBundle\Sandbox;

use ApiPlatform\Core\Bridge\Symfony\Bundle\ApiPlatformBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Kilip\UserBundle\DoyoUserBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new DoctrineBundle(),
            new ApiPlatformBundle(),
            new DoyoUserBundle(),
            new WebProfilerBundle(),
        ];
    }

    public function getProjectDir()
    {
        return realpath(__DIR__);
    }

    protected function configureRoutes(RoutingConfigurator $routes)
    {
        $routes->import($this->getProjectDir().'/config/{routes}/'.$this->environment.'/*.yaml');
        $routes->import($this->getProjectDir().'/config/{routes}/*.yaml');
    }

    protected function configureContainer(ContainerConfigurator $c)
    {
        $c->import($this->getProjectDir().'/config/*.yaml');

        if (is_dir($dir = $this->getProjectDir().'/config/'.$this->environment.'/*.yaml')) {
            $c->import($dir);
        }
    }
}

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
use Kilip\UserBundle\Contract\UserInterface;
use Kilip\UserBundle\DoyoUserBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RouteConfigurator;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;

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

    /**
     * @param RouteCollectionBuilder|RouteConfigurator $routes
     *
     * @throws \Symfony\Component\Config\Exception\LoaderLoadException
     */
    protected function configureRoutes($routes)
    {
        $routes->import($this->getProjectDir().'/config/{routes}/'.$this->environment.'/*.yaml');
        $routes->import($this->getProjectDir().'/config/{routes}/*.yaml');
    }

    /**
     * @param ContainerBuilder|ContainerConfigurator $c
     * @param LoaderInterface                        $loader
     *
     * @throws \Exception
     */
    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $loader->load($this->getProjectDir().'/config/*.yaml', 'glob');
        if (is_dir($dir = $this->getProjectDir().'/config/'.$this->environment.'/*.yaml')) {
            $loader->load($dir, 'glob');
        }
        $this->configureSecurity($c);
    }

    /**
     * @param ContainerConfigurator|ContainerBuilder $c
     */
    private function configureSecurity($c)
    {
        $alg = class_exists(NativePasswordEncoder::class) ? 'auto' : 'bcrypt';
        $securityConfig = [
            'encoders' => [
                UserInterface::class => $alg,
                //UserDocument::class => $alg,
                // Don't use plaintext in production!
                //UserInterface::class => 'plaintext',
            ],
            'providers' => [
                'chain_provider' => [
                    'chain' => [
                        'providers' => ['in_memory'],
                    ],
                ],
                'in_memory' => [
                    'memory' => [
                        'users' => [
                            'dunglas' => ['password' => 'kevin', 'roles' => 'ROLE_USER'],
                            'admin' => ['password' => 'kitten', 'roles' => 'ROLE_ADMIN'],
                        ],
                    ],
                ],
                'fos_userbundle' => ['id' => 'fos_user.user_provider.username_email'],
            ],
            'firewalls' => [
                'dev' => [
                    'pattern' => '^/(_(profiler|wdt|error)|css|images|js)/',
                    'security' => false,
                ],
                'default' => [
                    'provider' => 'chain_provider',
                    'http_basic' => null,
                    'anonymous' => null,
                    'stateless' => true,
                ],
            ],
            'access_control' => [
                ['path' => '^/', 'role' => 'IS_AUTHENTICATED_ANONYMOUSLY'],
            ],
        ];

        $c->prependExtensionConfig('security', $securityConfig);
    }
}

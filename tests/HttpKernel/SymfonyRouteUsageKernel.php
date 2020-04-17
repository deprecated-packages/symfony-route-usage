<?php

declare(strict_types=1);

namespace Migrify\SymfonyRouteUsage\Tests\HttpKernel;

use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Knp\DoctrineBehaviors\DoctrineBehaviorsBundle;
use Migrify\SymfonyRouteUsage\SymfonyRouteUsageBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

final class SymfonyRouteUsageKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/../config/config_test.yaml');
    }

    /**
     * @return BundleInterface[]
     */
    public function registerBundles(): iterable
    {
        return [
            new SymfonyRouteUsageBundle(),
            new DoctrineBehaviorsBundle(),
            // tests
            new DAMADoctrineTestBundle(),
            // symfony app
            new DoctrineBundle(),
            new FrameworkBundle(),
            new SecurityBundle(),
        ];
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/symfony_route_usage_test';
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/symfony_route_usage_test_log';
    }

    protected function configureRoutes(RouteCollectionBuilder $routeCollectionBuilder): void
    {
        $routeCollectionBuilder->import(__DIR__ . '/../config/routes.yaml', '/', 'YAML');
    }

    protected function configureContainer(ContainerBuilder $containerBuilder, LoaderInterface $loader): void
    {
    }
}

<?php

declare(strict_types=1);

namespace Migrify\SymfonyRouteUsage\Tests\HttpKernel;

use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

final class SymfonyRouteUsageKernel extends Kernel
{
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/../config/config_test.yaml');
        $loader->load(__DIR__ . '/../../config/config.yaml');
    }

    /**
     * @return BundleInterface[]
     */
    public function registerBundles(): iterable
    {
        return [new DoctrineBundle(), new FrameworkBundle(), new DAMADoctrineTestBundle()];
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/symfony_route_usage_test';
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/symfony_route_usage_test_log';
    }
}

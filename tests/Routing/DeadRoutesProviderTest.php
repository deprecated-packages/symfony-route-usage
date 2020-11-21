<?php

declare(strict_types=1);

namespace Migrify\SymfonyRouteUsage\Tests\Routing;

use Migrify\SymfonyRouteUsage\Routing\DeadRoutesProvider;
use Migrify\SymfonyRouteUsage\Tests\Helper\DatabaseLoaderHelper;
use Migrify\SymfonyRouteUsage\Tests\HttpKernel\SymfonyRouteUsageKernel;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class DeadRoutesProviderTest extends AbstractKernelTestCase
{
    /**
     * @var DeadRoutesProvider
     */
    private $deadRoutesProvider;

    protected function setUp(): void
    {
        $this->bootKernel(SymfonyRouteUsageKernel::class);

        $databaseLoaderHelper = new DatabaseLoaderHelper(self::$container);
        $databaseLoaderHelper->disableDoctrineLogger();
        $databaseLoaderHelper->createDatabase();

        $this->deadRoutesProvider = self::$container->get(DeadRoutesProvider::class);
    }

    public function test(): void
    {
        $deadRoutes = $this->deadRoutesProvider->provide();
        $this->assertCount(1, $deadRoutes);

        $this->assertArrayHasKey('acme_privacy', $deadRoutes);
    }
}

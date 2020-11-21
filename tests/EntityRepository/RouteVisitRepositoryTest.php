<?php

declare(strict_types=1);

namespace Migrify\SymfonyRouteUsage\Tests\EntityRepository;

use Migrify\SymfonyRouteUsage\Entity\RouteVisit;
use Migrify\SymfonyRouteUsage\EntityRepository\RouteVisitRepository;
use Migrify\SymfonyRouteUsage\Tests\Helper\DatabaseLoaderHelper;
use Migrify\SymfonyRouteUsage\Tests\HttpKernel\SymfonyRouteUsageKernel;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class RouteVisitRepositoryTest extends AbstractKernelTestCase
{
    /**
     * @var RouteVisitRepository
     */
    private $routeVisitRepository;

    protected function setUp(): void
    {
        $this->bootKernel(SymfonyRouteUsageKernel::class);

        $databaseLoaderHelper = new DatabaseLoaderHelper(self::$container);
        $databaseLoaderHelper->disableDoctrineLogger();
        $databaseLoaderHelper->createDatabase();

        $this->routeVisitRepository = self::$container->get(RouteVisitRepository::class);
    }

    public function test(): void
    {
        $routeVisit = new RouteVisit('some_route', "{'route':'params'}", 'SomeController', 'some_hash');

        $this->routeVisitRepository->save($routeVisit);

        $routeVisits = $this->routeVisitRepository->fetchAll();
        $this->assertCount(1, $routeVisits);

        $routeVisit = $routeVisits[0];
        $this->assertSame(1, $routeVisit->getVisitCount());
    }
}

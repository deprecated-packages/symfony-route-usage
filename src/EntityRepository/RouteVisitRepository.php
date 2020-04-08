<?php

declare(strict_types=1);

namespace Migrify\SymfonyRouteUsage\EntityRepository;

use Doctrine\ORM\EntityManagerInterface;
use Migrify\SymfonyRouteUsage\Entity\RouteVisit;
use Migrify\SymfonyRouteUsage\ValueObject\RouteUsageStat;
use Symplify\EasyHydrator\ArrayToValueObjectHydrator;

final class RouteVisitRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ArrayToValueObjectHydrator
     */
    private $arrayToValueObjectHydrator;

    public function __construct(
        EntityManagerInterface $entityManager,
        ArrayToValueObjectHydrator $arrayToValueObjectHydrator
    ) {
        $this->entityManager = $entityManager;
        $this->arrayToValueObjectHydrator = $arrayToValueObjectHydrator;
    }

    public function save(RouteVisit $routeVisit): void
    {
        $this->entityManager->persist($routeVisit);
        $this->entityManager->flush();
    }

    /**
     * @return RouteUsageStat[]
     */
    public function fetchAll(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from(RouteVisit::class, 'r');
        $queryBuilder->select('r.route, r.controller, r.routeParams, r.uniqueRouteHash, COUNT(r.id) as usage_count');
        $queryBuilder->groupBy('r.route, r.controller, r.routeParams, r.uniqueRouteHash');
        $queryBuilder->orderBy('usage_count', 'DESC');

        $query = $queryBuilder->getQuery();
        $result = $query->getResult();

        return $this->arrayToValueObjectHydrator->hydrateArrays($result, RouteUsageStat::class);
    }
}

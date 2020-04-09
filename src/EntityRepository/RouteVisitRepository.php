<?php

declare(strict_types=1);

namespace Migrify\SymfonyRouteUsage\EntityRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Migrify\SymfonyRouteUsage\Entity\RouteVisit;

final class RouteVisitRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ObjectRepository
     */
    private $objectRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $entityManager->getRepository(RouteVisit::class);
    }

    public function save(RouteVisit $routeVisit): void
    {
        $this->entityManager->persist($routeVisit);
        $this->entityManager->flush();
    }

    public function findByRouteHash(string $routeHash): ?RouteVisit
    {
        return $this->objectRepository->findOneBy(['routeHash' => $routeHash]);
    }

    /**
     * @return RouteVisit[]
     */
    public function fetchAll(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from(RouteVisit::class, 'r');
        $queryBuilder->select('r');
        $queryBuilder->orderBy('r.visitCount', 'DESC');

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }
}

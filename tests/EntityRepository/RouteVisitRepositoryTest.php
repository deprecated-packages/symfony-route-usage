<?php

declare(strict_types=1);

namespace Migrify\SymfonyRouteUsage\Tests\EntityRepository;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Migrify\SymfonyRouteUsage\Entity\RouteVisit;
use Migrify\SymfonyRouteUsage\EntityRepository\RouteVisitRepository;
use Migrify\SymfonyRouteUsage\Tests\HttpKernel\SymfonyRouteUsageKernel;
use Migrify\SymfonyRouteUsage\ValueObject\RouteUsageStat;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use PDOException;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;

final class RouteVisitRepositoryTest extends AbstractKernelTestCase
{
    /**
     * @var RouteVisitRepository
     */
    private $routeVisitRepository;

    protected function setUp(): void
    {
        $this->bootKernel(SymfonyRouteUsageKernel::class);

        $this->disableDoctrineLogger();

        $this->createDatabase();
        $this->createDatabaseSchema();

        $this->routeVisitRepository = self::$container->get(RouteVisitRepository::class);
    }

    public function test(): void
    {
        $routeVisit = new RouteVisit('some_route', "{'route':'params'}", 'SomeController', DateTime::from('now'));

        $this->routeVisitRepository->save($routeVisit);

        $routeUsageStats = $this->routeVisitRepository->fetchAll();
        $this->assertCount(1, $routeUsageStats);

        $routeUsageStat = $routeUsageStats[0];

        /** @var RouteUsageStat $routeUsageStat */
        $this->assertSame(1, $routeUsageStat->getUsageCount());
    }

    private function disableDoctrineLogger(): void
    {
        // @see https://stackoverflow.com/a/35222045/1348344
        // disable Doctrine logs in tests output
        $entityManager = self::$container->get('doctrine.orm.entity_manager');
        $entityManager->getConfiguration();
        $connection = $entityManager->getConnection();

        /** @var Configuration $configuration */
        $configuration = $connection->getConfiguration();
        $configuration->setSQLLogger(null);
    }

    /**
     * 1. create database, basically same as: "bin/console doctrine:database:create" in normal Symfony app
     */
    private function createDatabase(): void
    {
        /** @var Connection $connection */
        $connection = self::$container->get('doctrine.dbal.default_connection');
        $databaseName = self::$container->getParameter('database_name');

        $existingDatabases = $connection->getSchemaManager()->listDatabases();
        if (in_array($databaseName, $existingDatabases, true)) {
            return;
        }

        $databaseName = $connection->getDatabasePlatform()->quoteSingleIdentifier($databaseName);
        // somehow broken on my pc, see https://github.com/doctrine/dbal/pull/2671
        $connection->getSchemaManager()->createDatabase($databaseName);
    }

    /**
     * 2. create database schema without bin/console, basically same as: "bin/console doctrine:schema:create" in normal Symfony app
     */
    private function createDatabaseSchema(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::$container->get('doctrine.orm.entity_manager');

        $schemaTool = new SchemaTool($entityManager);

        try {
            $routeVisitClassMetadata = $entityManager->getClassMetadata(RouteVisit::class);
            $schemaTool->createSchema([$routeVisitClassMetadata]);
        } catch (PDOException $pDOException) {
            if (Strings::contains($pDOException->getMessage(), 'Duplicate table')) {
                // already crated
                return;
            }

            throw $pDOException;
        }
    }
}

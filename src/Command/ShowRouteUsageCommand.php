<?php

declare(strict_types=1);

namespace Migrify\SymfonyRouteUsage\Command;

use Migrify\SymfonyRouteUsage\EntityRepository\RouteVisitRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\PackageBuilder\Console\ShellCode;

final class ShowRouteUsageCommand extends Command
{
    /**
     * @var RouteVisitRepository
     */
    private $routeVisitRepository;

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    public function __construct(RouteVisitRepository $routeVisitRepository, SymfonyStyle $symfonyStyle)
    {
        $this->routeVisitRepository = $routeVisitRepository;
        $this->symfonyStyle = $symfonyStyle;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Show usage of routes');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tableHeadline = ['Visits', 'Controller', 'Route', 'Params', 'First Visit', 'Last Visit'];
        $tableData = [];

        $this->symfonyStyle->title('Used Routes by Visit Count');

        foreach ($this->routeVisitRepository->fetchAll() as $routeUsageStat) {
            $tableData[] = [
                'visit_count' => $routeUsageStat->getVisitCount(),
                'controller' => $routeUsageStat->getController(),
                'route' => $routeUsageStat->getRoute(),
                'params' => $routeUsageStat->getRouteParams(),
                'first_visit' => $routeUsageStat->getCreatedAt()->format('Y-m-d'),
                'last_visit' => $routeUsageStat->getUpdatedAt()->format('Y-m-d'),
            ];
        }

        $this->symfonyStyle->table($tableHeadline, $tableData);

        return ShellCode::SUCCESS;
    }
}

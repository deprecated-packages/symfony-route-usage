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
        $tableHeadline = ['Controller', 'Route', 'Params', 'Visit Count', 'First Visit', 'Last Visit'];
        $tableData = [];

        foreach ($this->routeVisitRepository->fetchAll() as $routeUsageStat) {
            $tableData[] = [
                'controller' => $routeUsageStat->getController(),
                'route' => $routeUsageStat->getRoute(),
                'params' => $routeUsageStat->getRouteParams(),
                'visit_count' => $routeUsageStat->getVisitCount(),
                'first_visit' => $routeUsageStat->getCreatedAt(),
                'last_visit' => $routeUsageStat->getUpdatedAt(),
            ];
        }

        $this->symfonyStyle->newLine();
        $this->symfonyStyle->table($tableHeadline, $tableData);

        return ShellCode::SUCCESS;
    }
}

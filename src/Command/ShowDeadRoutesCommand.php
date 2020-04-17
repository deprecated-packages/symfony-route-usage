<?php

declare(strict_types=1);

namespace Migrify\SymfonyRouteUsage\Command;

use Migrify\SymfonyRouteUsage\Route\DeadRoutesProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\Route;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\PackageBuilder\Console\ShellCode;

final class ShowDeadRoutesCommand extends Command
{
    /**
     * @var string[]
     */
    private const TABLE_HEADLINE = ['Route Name', 'Route Path', 'Controller'];

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    /**
     * @var DeadRoutesProvider
     */
    private $deadRoutesProvider;

    public function __construct(DeadRoutesProvider $deadRoutesProvider, SymfonyStyle $symfonyStyle)
    {
        $this->symfonyStyle = $symfonyStyle;
        $this->deadRoutesProvider = $deadRoutesProvider;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Display dead routes');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tableData = [];
        $this->symfonyStyle->title('Used Routes by Visit Count');

        /** @var Route $route */
        foreach ($this->deadRoutesProvider->provide() as $routeName => $route) {
            $tableData[] = [
                'route_name' => $routeName,
                'route_path' => $route->getPath(),
                'controller' => $route->getDefault('_controller'),
            ];
        }
        $this->symfonyStyle->table(self::TABLE_HEADLINE, $tableData);

        $otherCommandMessage = sprintf(
            'Do you want to see what routes are used? Run "bin/console %s"',
            CommandNaming::classToName(ShowRouteUsageCommand::class)
        );
        $this->symfonyStyle->note($otherCommandMessage);

        return ShellCode::SUCCESS;
    }
}

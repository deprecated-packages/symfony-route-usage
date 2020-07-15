<?php

declare(strict_types=1);

namespace Migrify\SymfonyRouteUsage;

use Migrify\SymfonyRouteUsage\DependencyInjection\SymfonyRouteUsageExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SymfonyRouteUsageBundle extends Bundle
{
    protected function createContainerExtension(): ?ExtensionInterface
    {
        return new SymfonyRouteUsageExtension();
    }
}

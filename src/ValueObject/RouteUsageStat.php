<?php

declare(strict_types=1);

namespace Migrify\SymfonyRouteUsage\ValueObject;

final class RouteUsageStat
{
    /**
     * @var string string
     */
    private $routeParams;

    /**
     * @var string
     */
    private $route;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var int
     */
    private $usageCount;

    public function __construct(string $route, string $controller, string $routeParams, int $usageCount)
    {
        $this->route = $route;
        $this->controller = $controller;

        $this->routeParams = $routeParams;
        $this->usageCount = $usageCount;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getRouteParams(): string
    {
        return $this->routeParams;
    }

    public function getUsageCount(): int
    {
        return $this->usageCount;
    }
}

<?php

declare(strict_types=1);

namespace Migrify\SymfonyRouteUsage\Route;

use Symfony\Component\HttpFoundation\Request;

final class RouteHashFactory
{
    public function createFromRequest(Request $request): string
    {
        $route = (string) $request->get('_route');
        $method = (string) $request->getMethod();

        return sha1($route . '_' . $method);
    }
}

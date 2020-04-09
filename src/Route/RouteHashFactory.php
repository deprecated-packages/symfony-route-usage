<?php

declare(strict_types=1);

namespace Migrify\SymfonyRouteUsage\Route;

use Nette\Utils\Json;
use Symfony\Component\HttpFoundation\Request;

final class RouteHashFactory
{
    public function createFromRequest(Request $request): string
    {
        $route = (string) $request->get('_route');
        $routeParams = Json::encode($request->get('_route_params'));

        return sha1($route . $routeParams);
    }
}

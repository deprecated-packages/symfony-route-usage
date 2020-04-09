<?php

declare(strict_types=1);

namespace Migrify\SymfonyRouteUsage\EntityFactory;

use Migrify\SymfonyRouteUsage\Entity\RouteVisit;
use Nette\Utils\Json;
use Symfony\Component\HttpFoundation\Request;

final class RouteVisitFactory
{
    public function createFromRequest(Request $request, string $routeHash): RouteVisit
    {
        /** @var string $route */
        $route = $request->get('_route');

        /** @var string $controller */
        $controller = $request->get('_controller');
        $routeParams = Json::encode($request->get('_route_params'));

        return new RouteVisit($route, $routeParams, $controller, $routeHash);
    }
}

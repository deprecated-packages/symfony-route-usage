<?php

declare(strict_types=1);

namespace Migrify\SymfonyRouteUsage\EntityFactory;

use Migrify\SymfonyRouteUsage\Entity\RouteVisit;
use Nette\Utils\DateTime;
use Nette\Utils\Json;
use Symfony\Component\HttpFoundation\Request;

final class RouteVisitFactory
{
    public function createFromRequest(Request $request): RouteVisit
    {
        $routeParams = Json::encode($request->get('_route_params'));
        $createdAt = new DateTime();

        return new RouteVisit($request->get('_route'), $routeParams, $request->get('_controller'), $createdAt);
    }
}

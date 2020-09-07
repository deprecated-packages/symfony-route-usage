<?php

declare(strict_types=1);

namespace Migrify\SymfonyRouteUsage\EventSubscriber;

use Migrify\SymfonyRouteUsage\EntityFactory\RouteVisitFactory;
use Migrify\SymfonyRouteUsage\EntityRepository\RouteVisitRepository;
use Migrify\SymfonyRouteUsage\Routing\RouteHashFactory;
use Nette\Utils\Strings;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class LogRouteUsageEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $routeUsageExcludeRouteRegex;

    /**
     * @var RouteVisitFactory
     */
    private $routeVisitFactory;

    /**
     * @var RouteVisitRepository
     */
    private $routeVisitRepository;

    /**
     * @var RouteHashFactory
     */
    private $routeHashFactory;

    public function __construct(
        RouteVisitRepository $routeVisitRepository,
        RouteVisitFactory $routeVisitFactory,
        ParameterBagInterface $parameterBag,
        RouteHashFactory $routeHashFactory
    ) {
        $this->routeVisitRepository = $routeVisitRepository;
        $this->routeVisitFactory = $routeVisitFactory;
        $this->routeUsageExcludeRouteRegex = $parameterBag->get('route_usage_exclude_route_regex');
        $this->routeHashFactory = $routeHashFactory;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::CONTROLLER => 'onController'];
    }

    public function onController(ControllerEvent $controllerEvent): void
    {
        $request = $controllerEvent->getRequest();
        if ($this->shouldSkipRequest($request)) {
            return;
        }

        $requestHash = $this->routeHashFactory->createFromRequest($request);

        $alreadyExistingRouteVisit = $this->routeVisitRepository->findByRouteHash($requestHash);
        if ($alreadyExistingRouteVisit !== null) {
            // update old one
            $alreadyExistingRouteVisit->increaseVisitCount();
            $this->routeVisitRepository->save($alreadyExistingRouteVisit);
        } else {
            // creat new one
            $routeVisit = $this->routeVisitFactory->createFromRequest($request, $requestHash);
            $this->routeVisitRepository->save($routeVisit);
        }
    }

    private function shouldSkipRequest(Request $request): bool
    {
        if ($this->shouldSkipRoute($request->get('_route'))) {
            return true;
        }

        // skip redirects
        $controller = $request->get('_controller');

        return Strings::startsWith($controller, RedirectController::class);
    }

    private function shouldSkipRoute(?string $route): bool
    {
        if ($route === null) {
            return true;
        }

        // is probably some debug-route
        if (Strings::startsWith((string) $route, '_')) {
            return true;
        }

        if ($this->shouldExcludeRoute($route)) {
            return true;
        }

        return $route === 'error_controller';
    }

    private function shouldExcludeRoute(string $route): bool
    {
        if ($this->routeUsageExcludeRouteRegex === '') {
            return false;
        }

        return (bool) Strings::match($route, $this->routeUsageExcludeRouteRegex);
    }
}

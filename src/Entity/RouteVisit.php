<?php

declare(strict_types=1);

namespace Migrify\SymfonyRouteUsage\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Nette\Utils\Json;

/**
 * @ORM\Entity
 */
class RouteVisit implements TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int|null
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $route;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private $routeParams;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $controller;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    private $routeHash;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $visitCount;

    public function __construct(string $route, string $routeParams, string $controller, string $routeHash)
    {
        $this->route = $route;
        $this->routeParams = $routeParams;
        $this->controller = $controller;
        $this->routeHash = $routeHash;
        $this->visitCount = 1;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getRouteParams(): string
    {
        $routeParams = Json::decode($this->routeParams, Json::FORCE_ARRAY);
        if ($routeParams === []) {
            return '';
        }

        $routeParamsForConsole = '';
        foreach ($routeParams as $paramName => $paramValue) {
            $routeParamsForConsole .= sprintf('%s: %s', $paramName, $paramValue) . PHP_EOL;
        }

        return rtrim($routeParamsForConsole);
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function increaseVisitCount(): void
    {
        ++$this->visitCount;
    }

    public function getVisitCount(): int
    {
        return $this->visitCount;
    }
}

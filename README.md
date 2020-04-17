# Detect Used and Unused Symfony Routes

[![Downloads total](https://img.shields.io/packagist/dt/migrify/symfony-route-usage.svg?style=flat-square)](https://packagist.org/packages/migrify/symfony-route-usage/stats)

*Inspired by [Route Usage](https://github.com/julienbourdeau/route-usage/) for Laravel.*

"This package keeps track of all requests to know what controller method, and when it was called. The goal is not to build some sort of analytics but to find out if there are unused endpoints or controller method.

After a few years, any projects have dead code and unused endpoint. Typically, you removed a link on your frontend, nothing ever links to that old /special-page. You want to remove it, but you're not sure. Have look at the route_usage table and figure out when this page was accessed for the last time. Last week? Better keep it for now. 3 years ago? REMOVE THE CODE!"

From [julienbourdeau/route-usage/](https://github.com/julienbourdeau/route-usage/).

## Install

```bash
composer require migrify/symfony-route-usage
```

Register bundle to your `config/bundles.php` (in case Flex misses it):

```php
return [
    Migrify\SymfonyRouteUsage\SymfonyRouteUsageBundle::class => ['all' => true],
];
```

## Usage

Show used routes:

```bash
bin/console show-route-usage
```

Show dead routes:

```bash
bin/console show-dead-routes
```


## Configuration

By default, `_*` and `error_controller` is excluded. If you want to exclude more routes, use regex parameter: 

```yaml
# config/services.yaml
parameters:
    route_usage_exclude_route_regex: '#legacy#'
```

<?php

declare(strict_types=1);

namespace Migrify\SymfonyRouteUsage\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class SymfonyRouteUsageExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $containerBuilder): void
    {
        $loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('config.yaml');
    }

    public function prepend(ContainerBuilder $containerBuilder): void
    {
        // @see https://symfony.com/doc/current/bundles/prepend_extension.html#more-than-one-bundle-using-prependextensioninterface
        $containerBuilder->prependExtensionConfig('doctrine', [
            'orm' => [
                'auto_mapping' => true,
                'mappings' => [
                    'Migrify\SymfonyRouteUsage\Entity' => [
                        'prefix' => 'Migrify\SymfonyRouteUsage\Entity',
                        'type' => 'annotation',
                        'dir' => __DIR__ . '/../../src/Entity',
                    ],
                ],
            ],
        ]);
    }
}

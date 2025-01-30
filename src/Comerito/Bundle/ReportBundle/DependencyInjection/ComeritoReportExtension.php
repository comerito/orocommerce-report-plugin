<?php

namespace Comerito\Bundle\ReportBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ComeritoReportExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('integration.yml');
        $loader->load('event_listeners.yml');
        $loader->load('form_types.yml');
        $loader->load('services.yml');
    }
}

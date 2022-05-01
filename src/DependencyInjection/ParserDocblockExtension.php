<?php

namespace Cadoteu\ParserDocblockBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ParserDocblockExtension extends Extension
{
    /**
     * Loads our service, accessible
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        //$this->loadConfiguration($configs, $container);
        //$loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        //$loader->load('parserdocblock.xml');
    }

    /**
     * Loads the configuration in, with any defaults
     */
    protected function loadConfiguration(array $configs, ContainerBuilder $container): void
    {
        //$configuration = new Configuration();
        //$config = $this->processConfiguration($configuration, $configs);
        //$container->setParameter('parserdocblock.options', $config);
    }
}

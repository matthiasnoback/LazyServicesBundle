<?php

namespace Matthias\LazyServicesBundle\DependencyInjection;

use Matthias\LazyServicesBundle\DependencyInjection\Compiler\LazyServicesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class MatthiasLazyServicesExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
        $processedConfig = $this->processConfiguration(new Configuration(), $config);

        $container->setParameter(
            LazyServicesPass::LAZY_SERVICE_IDS_PARAMETER,
            $processedConfig['lazy_service_ids']
        );
    }
}

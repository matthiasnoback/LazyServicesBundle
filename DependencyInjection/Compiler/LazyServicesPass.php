<?php

namespace Matthias\LazyServicesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LazyServicesPass implements CompilerPassInterface
{
    const LAZY_SERVICE_IDS_PARAMETER = 'matthias_lazy_services.lazy_service_ids';

    public function process(ContainerBuilder $container)
    {
        $lazyServiceIds = $container->getParameter(self::LAZY_SERVICE_IDS_PARAMETER);

        foreach ($lazyServiceIds as $lazyServiceId) {
            if (!$container->hasDefinition($lazyServiceId) && !$container->hasAlias($lazyServiceId)) {
                continue;
            }

            $definition = $container->findDefinition($lazyServiceId);
            $definition->setLazy(true);
        }
    }
}

<?php

namespace Matthias\LazyServicesBundle;

use Matthias\LazyServicesBundle\DependencyInjection\Compiler\LazyArgumentsPass;
use Matthias\LazyServicesBundle\DependencyInjection\Compiler\LazyServicesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MatthiasLazyServicesBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new LazyServicesPass());
        $container->addCompilerPass(new LazyArgumentsPass());
    }
}

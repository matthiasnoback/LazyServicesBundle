<?php

namespace Matthias\LazyServicesBundle;

use Matthias\LazyServicesBundle\DependencyInjection\Compiler\LazyArgumentsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MatthiasLazyServicesBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new LazyArgumentsPass());
    }
}

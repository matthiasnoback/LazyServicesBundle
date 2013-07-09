<?php

namespace Matthias\LazyServicesBundle\Tests;

use Matthias\LazyServicesBundle\DependencyInjection\Compiler\LazyArgumentsPass;
use Matthias\LazyServicesBundle\MatthiasLazyServicesBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MatthiasLazyServicesBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testRegistersLazyArgumentsPass()
    {
        $container = new ContainerBuilder();
        $bundle = new MatthiasLazyServicesBundle();
        $bundle->build($container);
        $compilerPasses = $container->getCompilerPassConfig()->getBeforeOptimizationPasses();
        foreach ($compilerPasses as $compilerPass) {
            if ($compilerPass instanceof LazyArgumentsPass) {
                return;
            }
        }

        $this->fail();
    }
}

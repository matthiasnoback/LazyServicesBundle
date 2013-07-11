<?php

namespace Matthias\LazyServicesBundle\Tests;

use Matthias\LazyServicesBundle\DependencyInjection\Compiler\LazyArgumentsPass;
use Matthias\LazyServicesBundle\DependencyInjection\Compiler\LazyServicesPass;
use Matthias\LazyServicesBundle\MatthiasLazyServicesBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MatthiasLazyServicesBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testRegistersLazyServicesAndLazyArgumentsPass()
    {
        $container = new ContainerBuilder();
        $bundle = new MatthiasLazyServicesBundle();
        $bundle->build($container);
        $compilerPasses = $container->getCompilerPassConfig()->getBeforeOptimizationPasses();
        $lazyServicesPassRegistered = false;
        $lazyArgumentsPassRegistered = false;
        foreach ($compilerPasses as $compilerPass) {
            if ($compilerPass instanceof LazyServicesPass) {
                $lazyServicesPassRegistered = true;
            } elseif ($compilerPass instanceof LazyArgumentsPass) {
                $lazyArgumentsPassRegistered = true;
            }
        }

        if (!$lazyServicesPassRegistered) {
            $this->fail();
        }

        if (!$lazyArgumentsPassRegistered) {
            $this->fail();
        }
    }
}

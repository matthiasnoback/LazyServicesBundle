<?php

namespace Matthias\LazyServicesBundle\Tests\DependencyInjection\Compiler;

use Matthias\LazyServicesBundle\DependencyInjection\Compiler\LazyServicesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class LazyServicesPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var LazyServicesPass
     */
    private $lazyServicesPass;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->lazyServicesPass = new LazyServicesPass();
    }

    protected function tearDown()
    {
        $this->container = null;
        $this->lazyServicesPass = null;
    }

    public function testMakesServicesLazyBasedOnParameter()
    {
        $this->container->setParameter(LazyServicesPass::LAZY_SERVICE_IDS_PARAMETER, array(
            'service_1'
        ));

        $definition1 = new Definition();
        $this->container->setDefinition('service_1', $definition1);
        $definition2 = new Definition();
        $this->container->setDefinition('service_2', $definition2);

        $this->lazyServicesPass->process($this->container);

        $this->assertSame(true, $definition1->isLazy());
        $this->assertSame(false, $definition2->isLazy());
    }

    public function testDoesNotFailWhenServiceDoesNotExist()
    {
        $this->container->setParameter(LazyServicesPass::LAZY_SERVICE_IDS_PARAMETER, array(
            'service'
        ));

        try {
            $this->lazyServicesPass->process($this->container);
        } catch (\Exception $e) {
            $this->fail('The compiler pass should not have failed because of a missing service');
        }
    }

    public function testMakesAliasedServicesLazyBasedOnParameter()
    {
        $this->container->setParameter(LazyServicesPass::LAZY_SERVICE_IDS_PARAMETER, array(
            'alias'
        ));

        $definition = new Definition();
        $this->container->setDefinition('service', $definition);
        $this->container->setAlias('alias', 'service');

        $this->lazyServicesPass->process($this->container);

        $this->assertSame(true, $definition->isLazy());
    }
}

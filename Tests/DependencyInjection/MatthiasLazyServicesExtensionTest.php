<?php

namespace Matthias\LazyServicesBundle\Tests\DependencyInjection;

use Matthias\LazyServicesBundle\DependencyInjection\Compiler\LazyServicesPass;
use Matthias\LazyServicesBundle\DependencyInjection\MatthiasLazyServicesExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MatthiasLazyServicesExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    public $container;

    /**
     * @var MatthiasLazyServicesExtension
     */
    public $extension;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new MatthiasLazyServicesExtension();
    }

    protected function tearDown()
    {
        $this->container = null;
        $this->extension = null;
    }

    public function testSetsParameterWithLazyServiceIds()
    {
        $lazyServiceIds = array(
            'service_1',
            'service_2'
        );

        $config = array(
            'matthias_lazy_services' => array(
                'lazy_service_ids' => $lazyServiceIds
            )
        );
        $this->extension->load($config, $this->container);

        $this->assertEquals(
            $lazyServiceIds,
            $this->container->getParameter(LazyServicesPass::LAZY_SERVICE_IDS_PARAMETER)
        );
    }
}

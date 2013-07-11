<?php

namespace Matthias\LazyServicesBundle\Tests\DependencyInjection;

use Matthias\LazyServicesBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Processor
     */
    private $processor;

    /**
     * @var Configuration
     */
    private $configuration;

    protected function setUp()
    {
        $this->processor = new Processor();
        $this->configuration = new Configuration();
    }

    protected function tearDown()
    {
        $this->processor = null;
        $this->configuration = null;
    }

    /**
     * @dataProvider configProvider
     */
    public function testProcessedConfiguration(array $configs, array $expectedProcessedConfig)
    {
        $tree = $this->configuration->getConfigTreeBuilder()->buildTree();

        $processedConfig = $this->processor->process($tree, $configs);

        $this->assertEquals($expectedProcessedConfig, $processedConfig);
    }

    public function configProvider()
    {
        return array(
            array(
                array(),
                array('lazy_service_ids' => array())
            ),
            array(
                array('matthias_lazy_services' => array()),
                array('lazy_service_ids' => array())
            ),
            array(
                array(
                    'matthias_lazy_services' => array(
                        'lazy_service_ids' => array('service')
                    )
                ),
                array('lazy_service_ids' => array('service'))
            )
        );
    }
}

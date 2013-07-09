<?php

namespace Matthias\LazyServicesBundle\Tests\DependencyInjection\Compiler;

use Matthias\LazyServicesBundle\DependencyInjection\Compiler\LazyArgumentsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class LazyArgumentsPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var LazyArgumentsPass
     */
    private $lazyArgumentsPass;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->lazyArgumentsPass = new LazyArgumentsPass();
    }

    protected function tearDown()
    {
        $this->container = null;
        $this->lazyArgumentsPass = null;
    }

    public function testFailsWhenKeyAttributeIsMissingForLazyArgumentAlias()
    {
        $definition = new Definition();
        $definition->addTag(LazyArgumentsPass::LAZY_ARGUMENT_TAG);
        $this->container->setDefinition('service', $definition);

        $this->setExpectedException('\InvalidArgumentException', LazyArgumentsPass::LAZY_ARGUMENT_KEY_ATTRIBUTE);
        $this->lazyArgumentsPass->process($this->container);
    }

    public function testFailsWhenKeyAttributePointsToNonExistentArgument()
    {
        $definition = new Definition();
        $definition->addTag(LazyArgumentsPass::LAZY_ARGUMENT_TAG, array(
            LazyArgumentsPass::LAZY_ARGUMENT_KEY_ATTRIBUTE => 'mailer'
        ));
        $this->container->setDefinition('service', $definition);

        $this->setExpectedException('\InvalidArgumentException', 'argument');
        $this->lazyArgumentsPass->process($this->container);
    }

    public function testFailsWhenKeyAttributePointsToArgumentThatIsNotAReference()
    {
        $definition = new Definition();
        $definition->setArguments(array(
            'mailer' => 'not a reference'
        ));
        $definition->addTag(LazyArgumentsPass::LAZY_ARGUMENT_TAG, array(
            LazyArgumentsPass::LAZY_ARGUMENT_KEY_ATTRIBUTE => 'mailer'
        ));
        $this->container->setDefinition('service', $definition);

        $this->setExpectedException('\InvalidArgumentException', 'reference');
        $this->lazyArgumentsPass->process($this->container);
    }

    public function testSetsReferredServiceDefinitionToLazy()
    {
        $definition = new Definition();
        $definition->setArguments(array(
            'mailer' => new Reference('mailer')
        ));
        $definition->addTag(LazyArgumentsPass::LAZY_ARGUMENT_TAG, array(
            LazyArgumentsPass::LAZY_ARGUMENT_KEY_ATTRIBUTE => 'mailer'
        ));
        $this->container->setDefinition('service', $definition);

        $mailerDefinition = new Definition();
        $this->container->setDefinition('mailer', $mailerDefinition);

        $this->lazyArgumentsPass->process($this->container);

        $this->assertTrue($mailerDefinition->isLazy());
    }

    public function testAlsoWorksWithNumericIndexes()
    {
        $definition = new Definition();
        $definition->setArguments(array(
            new Reference('event_dispatcher'),
            new Reference('mailer')
        ));
        $definition->addTag(LazyArgumentsPass::LAZY_ARGUMENT_TAG, array(
            LazyArgumentsPass::LAZY_ARGUMENT_KEY_ATTRIBUTE => '1'
        ));
        $this->container->setDefinition('service', $definition);

        $mailerDefinition = new Definition();
        $this->container->setDefinition('mailer', $mailerDefinition);

        $this->lazyArgumentsPass->process($this->container);

        $this->assertTrue($mailerDefinition->isLazy());
    }
}

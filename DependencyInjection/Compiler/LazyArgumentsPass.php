<?php

namespace Matthias\LazyServicesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class LazyArgumentsPass implements CompilerPassInterface
{
    const LAZY_ARGUMENT_TAG = 'lazy_argument';
    const LAZY_ARGUMENT_KEY_ATTRIBUTE = 'key';

    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds(self::LAZY_ARGUMENT_TAG) as $serviceId => $tags) {
            foreach ($tags as $tagAttributes) {
                $this->processTag($container, $serviceId, $tagAttributes);
            }
        }
    }

    private function processTag(ContainerBuilder $container, $serviceId, array $tagAttributes)
    {
        if (!array_key_exists(self::LAZY_ARGUMENT_KEY_ATTRIBUTE, $tagAttributes)) {
            throw new \InvalidArgumentException(sprintf(
                'The "%s" attribute for "%s" tag of service "%s" is mandatory',
                self::LAZY_ARGUMENT_KEY_ATTRIBUTE,
                self::LAZY_ARGUMENT_TAG,
                $serviceId
            ));
        }

        $key = $tagAttributes[self::LAZY_ARGUMENT_KEY_ATTRIBUTE];
        $definition = $container->findDefinition($serviceId);
        $arguments = $definition->getArguments();
        if (!array_key_exists($key, $arguments)) {
            throw new \InvalidArgumentException(sprintf(
                'Service "%s" has no argument with key "%s"',
                $serviceId,
                self::LAZY_ARGUMENT_KEY_ATTRIBUTE
            ));
        }

        $argument = $arguments[$key];
        if (!($argument instanceof Reference)) {
            throw new \InvalidArgumentException(sprintf(
                'Argument with key "%s" of service "%s" is not a reference to another service',
                $key,
                $serviceId
            ));
        }

        $referencedDefinition = $container->findDefinition((string)$argument);

        $referencedDefinition->setLazy(true);
    }
}

<?php

namespace App\DependencyInjection\CompilerPass;

use App\Service\FrontApiVersions\FrontApiContext;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FrontApiCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $contextDefinition = $container->findDefinition(FrontApiContext::class);

        $strategyServiceIds = array_keys(
            $container->findTaggedServiceIds('front_api_service')
        );

        foreach ($strategyServiceIds as $strategyServiceId) {
            $contextDefinition->addMethodCall(
                'addApiVersion',
                [new Reference($strategyServiceId)]
            );
        }
    }
}

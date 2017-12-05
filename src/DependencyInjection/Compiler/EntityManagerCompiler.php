<?php

namespace Wucdbm\Bundle\BannerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EntityManagerCompiler implements CompilerPassInterface {

    public function process(ContainerBuilder $container) {
        $managerName = $container->getParameter('wucdbm_banner.config.entity_manager_name');
        $factoryId = sprintf('doctrine.orm.%s_entity_manager', $managerName);

        if (!$container->has($factoryId)) {
            return;
        }

        $container->setAlias('wucdbm_banner.entity_manager', $factoryId);
    }

}
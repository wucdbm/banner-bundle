<?php

/*
 * This file is part of the BannerBundle package.
 *
 * (c) Martin Kirilov <wucdbm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wucdbm\Bundle\BannerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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

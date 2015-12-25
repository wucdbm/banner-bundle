<?php

namespace Wucdbm\Bundle\BannerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {

    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wucdbm_banner');

        $rootNode
            ->children()
//                ->scalarNode('show_positions_parameter')
//                    ->defaultValue('showpositions')
//                ->end()
            ->end();

        return $treeBuilder;
    }

}
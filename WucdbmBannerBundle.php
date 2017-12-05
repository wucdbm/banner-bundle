<?php

namespace Wucdbm\Bundle\BannerBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Wucdbm\Bundle\BannerBundle\DependencyInjection\Compiler\EntityManagerCompiler;

class WucdbmBannerBundle extends Bundle {

    public function build(ContainerBuilder $container) {
        parent::build($container);

        $container->addCompilerPass(new EntityManagerCompiler());
    }

}
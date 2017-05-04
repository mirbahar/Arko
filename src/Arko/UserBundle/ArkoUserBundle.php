<?php

namespace Arko\UserBundle;

use Arko\UserBundle\DependencyInjection\Compiler\PermissionCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ArkoUserBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new PermissionCompilerPass());
    }
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

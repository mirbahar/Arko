<?php

namespace Arko\UserBundle\Permission\Provider;

class SecurityPermissionProvider implements ProviderInterface
{
    public function getPermissions()
    {
        return array(
            'USER' => array('ROLE_USER','ROLE_ADMIN'),
            'ROLE_SUPER_ADMIN' => array('ROLE_SUPER_ADMIN'),
            'ROLE_EXTRA' => array('ROLE_SUPER_ADMIN','ROLE_EDIT')
        );
    }
}
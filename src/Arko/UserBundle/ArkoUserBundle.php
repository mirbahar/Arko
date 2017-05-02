<?php

namespace Arko\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ArkoUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
